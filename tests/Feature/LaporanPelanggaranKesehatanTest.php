<?php

namespace Tests\Feature;

use App\Filament\Pages\Laporan;
use App\Models\CatatanPelanggaran;
use App\Models\DataSantri;
use App\Models\KategoriPelanggaran;
use App\Models\Kelas;
use App\Models\Kesehatan;
use App\Models\LaporanArsip;
use App\Models\Pelanggaran;
use App\Models\User;
use App\Reports\KesehatanReport;
use App\Reports\PelanggaranReport;
use App\Reports\Support\StatusPoin;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class LaporanPelanggaranKesehatanTest extends TestCase
{
    use RefreshDatabase;

    private const PERIODE = ['tanggal_dari' => '2026-11-01', 'tanggal_sampai' => '2026-11-30'];

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    private function santri(string $nama, array $attributes = []): DataSantri
    {
        return DataSantri::create(['nama' => $nama, 'status' => 'Aktif', ...$attributes]);
    }

    private function pelanggaran(int $poin, ?KategoriPelanggaran $kategori = null): Pelanggaran
    {
        $kategori ??= KategoriPelanggaran::create(['nama_kategori' => 'Umum']);

        return Pelanggaran::create([
            'kategori_pelanggaran_id' => $kategori->id,
            'nama_pelanggaran' => "Pelanggaran {$poin} poin",
            'poin' => $poin,
        ]);
    }

    private function catat(DataSantri $santri, Pelanggaran $pelanggaran, string $tanggal = '2026-11-10'): CatatanPelanggaran
    {
        return CatatanPelanggaran::create([
            'data_santri_id' => $santri->id,
            'pelanggaran_id' => $pelanggaran->id,
            'tanggal_kejadian' => $tanggal,
        ]);
    }

    public function test_status_poin_uses_configured_thresholds(): void
    {
        $this->assertSame('Aman', StatusPoin::for(24));
        $this->assertSame('SP1', StatusPoin::for(25));
        $this->assertSame('SP1', StatusPoin::for(49));
        $this->assertSame('SP2', StatusPoin::for(50));
        $this->assertSame('SP3', StatusPoin::for(75));
        $this->assertSame('SP3', StatusPoin::for(200));
        $this->assertSame(25, StatusPoin::minimumSp());
    }

    public function test_pelanggaran_report_sums_points_per_santri_within_period(): void
    {
        $berat = $this->pelanggaran(30);
        $ringan = $this->pelanggaran(10);
        $ahmad = $this->santri('Ahmad');
        $budi = $this->santri('Budi');

        $this->catat($ahmad, $berat);
        $this->catat($ahmad, $berat);
        $this->catat($ahmad, $ringan, '2026-10-31'); // di luar periode
        $this->catat($budi, $ringan);

        $report = new PelanggaranReport;
        $result = $report->build($report->validateFilters(self::PERIODE));

        $this->assertSame(3, $result->rowCount());

        $rekap = $result->tables[0]['rows'];
        $this->assertSame(['Ahmad', 'Budi'], array_column($rekap, 'nama'));
        $this->assertSame([60, 10], array_column($rekap, 'total_poin'));
        $this->assertSame(['SP2', 'Aman'], array_column($rekap, 'status'));
        $this->assertSame('sorot sp2', $rekap[0]['_class']);
        $this->assertNull($rekap[1]['_class']);

        $this->assertSame(70, $result->summary['Total']['Total poin']);
        $this->assertSame(1, $result->summary['Status Santri']['SP2']);
        $this->assertSame(1, $result->summary['Status Santri']['Aman']);
        $this->assertSame('01 November 2026 s.d. 30 November 2026', $result->filterDescriptions['Periode']);
    }

    public function test_pelanggaran_report_can_show_only_sp_santri(): void
    {
        $berat = $this->pelanggaran(25);
        $ringan = $this->pelanggaran(5);
        $ahmad = $this->santri('Ahmad');
        $budi = $this->santri('Budi');

        $this->catat($ahmad, $berat);
        $this->catat($ahmad, $ringan);
        $this->catat($budi, $ringan);

        $report = new PelanggaranReport;
        $result = $report->build($report->validateFilters([...self::PERIODE, 'hanya_sp' => '1']));

        $this->assertSame(['Ahmad', 'Ahmad'], array_column($result->rows, 'nama'));
        $this->assertSame('Hanya santri berstatus SP', $result->filterDescriptions['Status']);
    }

    public function test_pelanggaran_report_filters_by_kelas_and_kategori(): void
    {
        $kelas = Kelas::create(['kd_kelas' => 'K1', 'nama_kelas' => '7A', 'tingkat' => '7', 'pegawai_id' => '1']);
        $kategoriA = KategoriPelanggaran::create(['nama_kategori' => 'Kedisiplinan']);
        $kategoriB = KategoriPelanggaran::create(['nama_kategori' => 'Kebersihan']);
        $ahmad = $this->santri('Ahmad', ['kelas_id' => $kelas->id]);
        $budi = $this->santri('Budi');

        $this->catat($ahmad, $this->pelanggaran(5, $kategoriA));
        $this->catat($ahmad, $this->pelanggaran(5, $kategoriB));
        $this->catat($budi, $this->pelanggaran(5, $kategoriA));

        $report = new PelanggaranReport;
        $result = $report->build($report->validateFilters([
            ...self::PERIODE,
            'kelas_id' => $kelas->id,
            'kategori_pelanggaran_id' => $kategoriA->id,
        ]));

        $this->assertSame(1, $result->rowCount());
        $this->assertSame('Ahmad', $result->rows[0]['nama']);
        $this->assertSame('Kedisiplinan', $result->rows[0]['kategori']);
    }

    public function test_periode_is_required_and_limited_to_one_year(): void
    {
        $report = new PelanggaranReport;

        try {
            $report->validateFilters([]);
            $this->fail('Periode kosong seharusnya ditolak.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('tanggal_dari', $exception->errors());
            $this->assertArrayHasKey('tanggal_sampai', $exception->errors());
        }

        try {
            $report->validateFilters(['tanggal_dari' => '2025-01-01', 'tanggal_sampai' => '2026-06-01']);
            $this->fail('Rentang lebih dari 1 tahun seharusnya ditolak.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Rentang tanggal maksimal 1 tahun.'], $exception->errors()['tanggal_sampai']);
        }

        try {
            $report->validateFilters(['tanggal_dari' => '2026-11-30', 'tanggal_sampai' => '2026-11-01']);
            $this->fail('Tanggal sampai sebelum tanggal dari seharusnya ditolak.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('tanggal_sampai', $exception->errors());
        }

        $this->assertSame(
            ['tanggal_dari' => '2025-11-01', 'tanggal_sampai' => '2026-11-01'],
            $report->validateFilters(['tanggal_dari' => '2025-11-01', 'tanggal_sampai' => '2026-11-01']),
        );
    }

    public function test_kesehatan_report_filters_and_summarises(): void
    {
        $ahmad = $this->santri('Ahmad');
        $budi = $this->santri('Budi');

        Kesehatan::create(['data_santri_id' => $ahmad->id, 'nama_penyakit' => 'demam', 'tanggal_sakit' => '2026-11-02', 'is_sembuh' => true]);
        Kesehatan::create(['data_santri_id' => $ahmad->id, 'nama_penyakit' => 'Demam', 'tanggal_sakit' => '2026-11-20', 'is_sembuh' => false]);
        Kesehatan::create(['data_santri_id' => $budi->id, 'nama_penyakit' => 'Flu', 'tanggal_sakit' => '2026-11-05', 'is_sembuh' => false]);
        Kesehatan::create(['data_santri_id' => $budi->id, 'nama_penyakit' => 'Flu', 'tanggal_sakit' => '2026-12-01', 'is_sembuh' => false]);

        $report = new KesehatanReport;
        $semua = $report->build($report->validateFilters(self::PERIODE));

        $this->assertSame(3, $semua->rowCount());
        $this->assertSame(['Demam' => 2, 'Flu' => 1], $semua->summary['Penyakit Terbanyak']);
        $this->assertSame(['Sembuh' => 1, 'Belum sembuh' => 2], $semua->summary['Status']);
        $this->assertSame(2, $semua->summary['Total']['Santri']);
        $this->assertSame('sorot', $semua->rows[1]['_class']);

        $belum = $report->build($report->validateFilters([...self::PERIODE, 'status' => 'belum']));
        $this->assertSame(['Budi', 'Ahmad'], array_column($belum->rows, 'nama'));
        $this->assertSame('Belum sembuh', $belum->filterDescriptions['Status']);
    }

    public function test_pelanggaran_and_kesehatan_pdf_endpoints_work(): void
    {
        $admin = User::factory()->create(['email' => 'admin@siadjar.com']);
        $ahmad = $this->santri('Ahmad');
        $this->catat($ahmad, $this->pelanggaran(80));
        Kesehatan::create(['data_santri_id' => $ahmad->id, 'nama_penyakit' => 'Flu', 'tanggal_sakit' => '2026-11-05']);

        foreach (['pelanggaran', 'kesehatan'] as $slug) {
            $response = $this->actingAs($admin)
                ->get(route('laporan.pdf', ['slug' => $slug, 'filters' => self::PERIODE]))
                ->assertOk()
                ->assertHeader('Content-Type', 'application/pdf');

            $this->assertStringStartsWith('%PDF', $response->getContent());
        }

        $this->assertSame(['kesehatan', 'pelanggaran'], LaporanArsip::orderBy('report_slug')->pluck('report_slug')->all());

        $this->actingAs($admin)
            ->get(route('laporan.pdf', ['slug' => 'pelanggaran']))
            ->assertSessionHasErrors(['tanggal_dari', 'tanggal_sampai']);
    }

    public function test_laporan_page_fills_default_periode_when_switching_report(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'admin@siadjar.com']));
        Filament::setCurrentPanel('admin');

        Livewire::test(Laporan::class)
            ->set('data.report', 'pelanggaran')
            ->assertSet('data.orientasi', 'landscape')
            ->assertSet('data.filters.tanggal_dari', now()->startOfMonth()->toDateString())
            ->assertSet('data.filters.tanggal_sampai', now()->endOfMonth()->toDateString())
            ->set('data.filters.tanggal_dari', null)
            ->callAction('unduhPdf')
            ->assertHasErrors(['data.filters.tanggal_dari']);
    }
}
