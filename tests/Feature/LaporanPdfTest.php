<?php

namespace Tests\Feature;

use App\Filament\Pages\Laporan;
use App\Filament\Resources\LaporanArsips\Pages\ListLaporanArsips;
use App\Models\DataSantri;
use App\Models\Kelas;
use App\Models\LaporanArsip;
use App\Models\User;
use App\Reports\SantriReport;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class LaporanPdfTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    private function admin(): User
    {
        return User::factory()->create(['email' => 'admin@siadjar.com']);
    }

    private function santri(array $attributes = []): DataSantri
    {
        return DataSantri::create(array_merge([
            'nama' => fake()->name(),
            'jk' => 'Laki-laki',
            'status' => 'Aktif',
            'jenjang' => 'SMP',
            'tahun_masuk' => '2025',
        ], $attributes));
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('laporan.pdf', ['slug' => 'santri']))
            ->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_user_outside_siadjar_domain_is_forbidden(): void
    {
        $user = User::factory()->create(['email' => 'orang@example.com']);

        $this->actingAs($user)
            ->get(route('laporan.pdf', ['slug' => 'santri']))
            ->assertForbidden();
    }

    public function test_unknown_report_returns_not_found(): void
    {
        $this->actingAs($this->admin())
            ->get(route('laporan.pdf', ['slug' => 'tidak-ada']))
            ->assertNotFound();
    }

    public function test_santri_report_generates_pdf_and_archives_it(): void
    {
        $admin = $this->admin();
        $this->santri(['nama' => 'Ahmad']);
        $this->santri(['nama' => 'Budi', 'status' => 'Tidak Aktif']);

        $response = $this->actingAs($admin)
            ->get(route('laporan.pdf', [
                'slug' => 'santri',
                'orientasi' => 'landscape',
                'filters' => ['status' => 'Aktif'],
            ]));

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
        $this->assertStringContainsString('inline; filename=laporan-santri-', $response->headers->get('Content-Disposition'));

        $arsip = LaporanArsip::sole();
        $this->assertSame('santri', $arsip->report_slug);
        $this->assertSame('landscape', $arsip->orientasi);
        $this->assertSame(1, $arsip->jumlah_baris);
        $this->assertSame(['status' => 'Aktif'], $arsip->filters);
        $this->assertSame('Status: Aktif', $arsip->keterangan_filter);
        $this->assertTrue($arsip->user->is($admin));
        Storage::disk('local')->assertExists($arsip->file_path);
        $this->assertSame(Storage::disk('local')->size($arsip->file_path), $arsip->file_size);
    }

    public function test_download_flag_returns_attachment(): void
    {
        $this->santri();

        $this->actingAs($this->admin())
            ->get(route('laporan.pdf', ['slug' => 'santri', 'unduh' => 1]))
            ->assertOk()
            ->assertDownload();
    }

    public function test_invalid_filter_value_is_rejected(): void
    {
        $this->actingAs($this->admin())
            ->get(route('laporan.pdf', ['slug' => 'santri', 'filters' => ['status' => 'Hacker']]))
            ->assertSessionHasErrors('status');

        $this->assertSame(0, LaporanArsip::count());
    }

    public function test_unregistered_filter_keys_are_ignored(): void
    {
        $this->santri();

        $this->actingAs($this->admin())
            ->get(route('laporan.pdf', ['slug' => 'santri', 'filters' => ['nik' => '123']]))
            ->assertOk();

        $this->assertSame([], LaporanArsip::sole()->filters);
    }

    public function test_report_exceeding_row_limit_is_rejected(): void
    {
        config(['laporan.max_baris' => 1]);
        $this->santri();
        $this->santri();

        $this->actingAs($this->admin())
            ->get(route('laporan.pdf', ['slug' => 'santri']))
            ->assertStatus(422);

        $this->assertSame(0, LaporanArsip::count());
    }

    public function test_santri_report_applies_filters(): void
    {
        $kelas = Kelas::create(['kd_kelas' => 'K1', 'nama_kelas' => '7A', 'tingkat' => '7', 'pegawai_id' => '1']);
        $this->santri(['nama' => 'Cocok', 'kelas_id' => $kelas->id, 'jk' => 'Perempuan']);
        $this->santri(['nama' => 'Beda Kelas', 'jk' => 'Perempuan']);
        $this->santri(['nama' => 'Beda JK', 'kelas_id' => $kelas->id, 'jk' => 'Laki-laki']);

        $report = new SantriReport;
        $filters = $report->validateFilters(['kelas_id' => (string) $kelas->id, 'jk' => 'Perempuan', 'status' => '']);
        $result = $report->build($filters);

        $this->assertEquals(['kelas_id' => (string) $kelas->id, 'jk' => 'Perempuan'], $filters);
        $this->assertSame(['Cocok'], array_column($result->rows, 'nama'));
        $this->assertSame('7A', $result->rows[0]['kelas']);
        $this->assertSame(['Jenis Kelamin' => 'Perempuan', 'Kelas' => '7A'], $result->filterDescriptions);
        $this->assertSame(1, $result->summary['Jenis Kelamin']['Perempuan']);
    }

    public function test_archived_report_can_be_downloaded_and_viewed(): void
    {
        $admin = $this->admin();
        $this->santri();
        $this->actingAs($admin)->get(route('laporan.pdf', ['slug' => 'santri']))->assertOk();
        $arsip = LaporanArsip::sole();

        $this->actingAs($admin)
            ->get(route('laporan.arsip.unduh', $arsip))
            ->assertOk()
            ->assertDownload($arsip->downloadFileName());

        $response = $this->actingAs($admin)
            ->get(route('laporan.arsip.unduh', ['laporanArsip' => $arsip, 'lihat' => 1]))
            ->assertOk();
        $this->assertStringStartsWith('inline;', $response->headers->get('Content-Disposition'));
    }

    public function test_archive_download_returns_not_found_when_file_missing(): void
    {
        $admin = $this->admin();
        $this->santri();
        $this->actingAs($admin)->get(route('laporan.pdf', ['slug' => 'santri']))->assertOk();
        $arsip = LaporanArsip::sole();
        Storage::disk('local')->delete($arsip->file_path);

        $this->actingAs($admin)
            ->get(route('laporan.arsip.unduh', $arsip))
            ->assertNotFound();
    }

    public function test_laporan_page_builds_pdf_url_from_form(): void
    {
        $this->actingAs($this->admin());
        Filament::setCurrentPanel('admin');
        $this->santri();

        Livewire::test(Laporan::class)
            ->assertOk()
            ->assertSet('data.report', 'santri')
            ->assertSet('data.orientasi', 'landscape')
            ->set('data.filters.status', 'Aktif')
            ->callAction('bukaPdf')
            ->assertHasNoErrors()
            ->assertNotNotified();
    }

    public function test_laporan_page_warns_when_row_limit_exceeded(): void
    {
        config(['laporan.max_baris' => 1]);
        $this->actingAs($this->admin());
        Filament::setCurrentPanel('admin');
        $this->santri();
        $this->santri();

        Livewire::test(Laporan::class)
            ->callAction('unduhPdf')
            ->assertNotified('Data terlalu banyak')
            ->assertNoRedirect();
    }

    public function test_laporan_page_redirects_to_download_url(): void
    {
        $this->actingAs($this->admin());
        Filament::setCurrentPanel('admin');
        $this->santri();

        Livewire::test(Laporan::class)
            ->set('data.filters.status', 'Aktif')
            ->callAction('unduhPdf')
            ->assertRedirect(route('laporan.pdf', [
                'slug' => 'santri',
                'orientasi' => 'landscape',
                'filters' => ['status' => 'Aktif'],
                'link' => 1,
                'unduh' => 1,
            ]));
    }

    public function test_arsip_list_page_renders(): void
    {
        $this->actingAs($this->admin());
        Filament::setCurrentPanel('admin');
        $this->santri();
        $this->get(route('laporan.pdf', ['slug' => 'santri']))->assertOk();

        Livewire::test(ListLaporanArsips::class)
            ->assertOk()
            ->assertCanSeeTableRecords(LaporanArsip::all());
    }
}
