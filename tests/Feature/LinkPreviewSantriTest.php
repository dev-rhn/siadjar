<?php

namespace Tests\Feature;

use App\Filament\Pages\Laporan;
use App\Filament\Resources\LaporanArsips\Pages\ListLaporanArsips;
use App\Models\CatatanPelanggaran;
use App\Models\DataSantri;
use App\Models\KategoriPelanggaran;
use App\Models\Kesehatan;
use App\Models\LaporanAksesLog;
use App\Models\LaporanArsip;
use App\Models\Pelanggaran;
use App\Models\User;
use App\Reports\GeneratedReport;
use App\Reports\PelanggaranReport;
use App\Reports\ReportPdfGenerator;
use App\Reports\SantriReport;
use App\Reports\Support\SantriPreviewLink;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class LinkPreviewSantriTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('public');
        $this->admin = User::factory()->create(['email' => 'admin@siadjar.com']);
    }

    private function santri(string $nama, array $attributes = []): DataSantri
    {
        return DataSantri::create([
            'nama' => $nama,
            'status' => 'Aktif',
            'nik' => fake()->unique()->numerify('3201############'),
            'no_kk' => '3201999988887777',
            'nama_ayah' => 'Ayah Rahasia',
            ...$attributes,
        ]);
    }

    private function cetak(array $santriFilters = [], ?string $pin = null): GeneratedReport
    {
        return app(ReportPdfGenerator::class)->generate(
            new SantriReport,
            $santriFilters,
            'landscape',
            $this->admin,
            denganLink: true,
            pin: $pin,
        );
    }

    private function catatPelanggaran(DataSantri $santri, int $poin, string $tanggal): void
    {
        $kategori = KategoriPelanggaran::firstOrCreate(['nama_kategori' => 'Umum']);
        $pelanggaran = Pelanggaran::create([
            'kategori_pelanggaran_id' => $kategori->id,
            'nama_pelanggaran' => "Pelanggaran {$poin} poin {$tanggal}",
            'poin' => $poin,
        ]);

        CatatanPelanggaran::create([
            'data_santri_id' => $santri->id,
            'pelanggaran_id' => $pelanggaran->id,
            'tanggal_kejadian' => $tanggal,
        ]);
    }

    public function test_pdf_with_link_embeds_signed_preview_links_and_stores_only_hash(): void
    {
        $ahmad = $this->santri('Ahmad');

        $generated = $this->cetak();
        $arsip = $generated->arsip;

        $this->assertNotNull($generated->kunciAkses);
        $this->assertSame(hash('sha256', $generated->kunciAkses), $arsip->akses_kunci_hash);
        $this->assertNotSame($generated->kunciAkses, $arsip->akses_kunci_hash);
        $this->assertTrue($arsip->akses_berlaku_sampai->between(now()->addDays(29), now()->addDays(31)));
        $this->assertSame(LaporanArsip::AKSES_AKTIF, $arsip->statusAkses());
        $this->assertStringContainsString('/p/'.$generated->kunciAkses.'/'.$ahmad->id.'/', $generated->content);
    }

    public function test_pdf_without_link_has_no_preview_links(): void
    {
        $this->santri('Ahmad');

        $generated = app(ReportPdfGenerator::class)->generate(new SantriReport, [], 'portrait', $this->admin);

        $this->assertNull($generated->kunciAkses);
        $this->assertNull($generated->arsip->akses_kunci_hash);
        $this->assertSame(LaporanArsip::AKSES_TANPA_LINK, $generated->arsip->statusAkses());
        $this->assertStringNotContainsString('/p/', $generated->content);
    }

    public function test_pdf_endpoint_link_flag_controls_links(): void
    {
        $this->santri('Ahmad');

        $this->actingAs($this->admin)->get(route('laporan.pdf', ['slug' => 'santri', 'link' => 1]))->assertOk();
        $this->actingAs($this->admin)->get(route('laporan.pdf', ['slug' => 'santri']))->assertOk();

        $this->assertSame([true, false], LaporanArsip::orderBy('id')->get()->map->punyaLinkPreview()->all());
    }

    public function test_valid_link_shows_preview_without_sensitive_data_and_logs_access(): void
    {
        $ahmad = $this->santri('Ahmad Fauzi');
        Kesehatan::create(['data_santri_id' => $ahmad->id, 'nama_penyakit' => 'Demam Tinggi', 'tanggal_sakit' => '2026-11-02']);
        $this->catatPelanggaran($ahmad, 30, '2026-11-03');
        $generated = $this->cetak();

        $response = $this->get(SantriPreviewLink::url($generated->kunciAkses, $ahmad->id));

        $response->assertOk()
            ->assertSee('Ahmad Fauzi')
            ->assertSee('Demam Tinggi')
            ->assertSee('Pelanggaran 30 poin 2026-11-03')
            ->assertSee('SP1')
            ->assertDontSee($ahmad->nik)
            ->assertDontSee('3201999988887777')
            ->assertDontSee('Ayah Rahasia')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertHeader('Referrer-Policy', 'no-referrer');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));

        $log = LaporanAksesLog::sole();
        $this->assertSame($generated->arsip->id, $log->laporan_arsip_id);
        $this->assertSame($ahmad->id, $log->data_santri_id);
    }

    public function test_tampered_link_is_rejected_without_leaking_data(): void
    {
        $ahmad = $this->santri('Ahmad');
        $budi = $this->santri('Budi');
        $kunci = $this->cetak(['status' => 'Aktif'])->kunciAkses;
        $tandaTanganAhmad = SantriPreviewLink::tandaTangan($kunci, $ahmad->id);

        // Ganti id santri, pertahankan tanda tangan milik santri lain.
        $this->get(route('santri-preview.show', ['kunci' => $kunci, 'santri' => $budi->id, 'tandaTangan' => $tandaTanganAhmad]))
            ->assertNotFound()
            ->assertSee('Link tidak berlaku')
            ->assertDontSee('Ahmad')
            ->assertDontSee('Budi');

        // Kunci salah dengan tanda tangan yang "cocok" untuk kunci itu.
        $kunciPalsu = str_repeat('a', 40);
        $this->get(SantriPreviewLink::url($kunciPalsu, $ahmad->id))->assertNotFound();

        // Tanda tangan diubah.
        $this->get(route('santri-preview.show', ['kunci' => $kunci, 'santri' => $ahmad->id, 'tandaTangan' => str_repeat('0', 32)]))
            ->assertNotFound();

        $this->assertSame(0, LaporanAksesLog::count());
    }

    public function test_expired_or_revoked_link_is_rejected(): void
    {
        $ahmad = $this->santri('Ahmad');
        $generated = $this->cetak();
        $url = SantriPreviewLink::url($generated->kunciAkses, $ahmad->id);

        $this->travel(31)->days();
        $this->get($url)->assertNotFound();
        $this->assertSame(LaporanArsip::AKSES_KEDALUWARSA, $generated->arsip->fresh()->statusAkses());

        $this->travelBack();
        $this->get($url)->assertOk();

        $generated->arsip->update(['akses_dicabut_at' => now()]);
        $this->get($url)->assertNotFound()->assertDontSee('Ahmad');
    }

    public function test_pin_protects_preview_and_unlocks_whole_pdf_for_session(): void
    {
        $ahmad = $this->santri('Ahmad');
        $budi = $this->santri('Budi');
        $generated = $this->cetak(pin: '123456');
        $urlAhmad = SantriPreviewLink::url($generated->kunciAkses, $ahmad->id);
        $urlBudi = SantriPreviewLink::url($generated->kunciAkses, $budi->id);

        $this->get($urlAhmad)->assertOk()->assertSee('Masukkan PIN')->assertDontSee('Ahmad');

        $this->from($urlAhmad)->post($urlAhmad, ['pin' => '000000'])
            ->assertRedirect($urlAhmad)
            ->assertSessionHasErrors('pin');

        $this->post($urlAhmad, ['pin' => '123456'])->assertRedirect($urlAhmad);
        $this->get($urlAhmad)->assertOk()->assertSee('Ahmad');
        $this->get($urlBudi)->assertOk()->assertSee('Budi');

        $this->travel(121)->minutes();
        $this->get($urlBudi)->assertSee('Masukkan PIN');
    }

    public function test_pin_form_locks_after_too_many_wrong_attempts(): void
    {
        $ahmad = $this->santri('Ahmad');
        $url = SantriPreviewLink::url($this->cetak(pin: '123456')->kunciAkses, $ahmad->id);

        foreach (range(1, 5) as $percobaan) {
            $this->from($url)->post($url, ['pin' => '999999']);
        }

        // PIN benar pun ditolak selama terkunci.
        $this->from($url)->post($url, ['pin' => '123456'])->assertRedirect($url);
        $this->get($url)->assertSee('Terlalu banyak percobaan salah')->assertDontSee('Ahmad</h1>', false);
    }

    public function test_preview_counts_sp_points_within_report_period_and_shows_total(): void
    {
        $ahmad = $this->santri('Ahmad');
        $this->catatPelanggaran($ahmad, 30, '2026-11-05');
        $this->catatPelanggaran($ahmad, 50, '2026-09-01'); // di luar periode

        $generated = app(ReportPdfGenerator::class)->generate(
            new PelanggaranReport,
            ['tanggal_dari' => '2026-11-01', 'tanggal_sampai' => '2026-11-30'],
            'landscape',
            $this->admin,
            denganLink: true,
        );

        $this->get(SantriPreviewLink::url($generated->kunciAkses, $ahmad->id))
            ->assertOk()
            ->assertSeeInOrder(['Poin periode', '30', 'Status', 'SP1', 'Total poin keseluruhan', '80'])
            ->assertSee('Pelanggaran 50 poin 2026-09-01');
    }

    public function test_pdf_endpoint_uses_pin_handed_over_through_session(): void
    {
        $ahmad = $this->santri('Ahmad');

        $this->actingAs($this->admin)
            ->withSession([SantriPreviewLink::PIN_SESSION_PREFIX.str_repeat('t', 32) => '654321'])
            ->get(route('laporan.pdf', ['slug' => 'santri', 'link' => 1, 'pin_token' => str_repeat('t', 32)]))
            ->assertOk()
            ->assertSessionMissing(SantriPreviewLink::PIN_SESSION_PREFIX.str_repeat('t', 32));

        $arsip = LaporanArsip::sole();
        $this->assertTrue(Hash::check('654321', $arsip->akses_pin_hash));
    }

    public function test_laporan_page_shows_pin_once_and_adds_pin_token_to_url(): void
    {
        $this->actingAs($this->admin);
        Filament::setCurrentPanel('admin');
        $this->santri('Ahmad');

        $component = Livewire::test(Laporan::class)
            ->assertSet('data.link_preview', true)
            ->assertSet('data.pin', false)
            ->set('data.pin', true)
            ->callAction('unduhPdf')
            ->assertNotified();

        $redirect = $component->effects['redirect'];
        $this->assertStringContainsString('link=1', $redirect);
        $this->assertMatchesRegularExpression('/pin_token=[A-Za-z0-9]{32}/', $redirect);
    }

    public function test_arsip_actions_revoke_extend_and_reset_pin(): void
    {
        $this->actingAs($this->admin);
        Filament::setCurrentPanel('admin');
        $ahmad = $this->santri('Ahmad');
        $generated = $this->cetak(pin: '111111');
        $arsip = $generated->arsip;

        $this->travel(40)->days();
        Livewire::test(ListLaporanArsips::class)
            ->callTableAction('perpanjangAkses', $arsip);
        $this->assertTrue($arsip->fresh()->aksesAktif());

        Livewire::test(ListLaporanArsips::class)
            ->callTableAction('resetPin', $arsip)
            ->assertNotified();
        $this->assertFalse(Hash::check('111111', $arsip->fresh()->akses_pin_hash));

        Livewire::test(ListLaporanArsips::class)
            ->callTableAction('cabutAkses', $arsip);
        $this->assertSame(LaporanArsip::AKSES_DICABUT, $arsip->fresh()->statusAkses());
        $this->get(SantriPreviewLink::url($generated->kunciAkses, $ahmad->id))->assertNotFound();

        Livewire::test(ListLaporanArsips::class)
            ->assertTableActionHidden('cabutAkses', $arsip)
            ->assertTableActionHidden('perpanjangAkses', $arsip)
            ->assertTableActionHidden('resetPin', $arsip);
    }

    public function test_cetak_ulang_creates_new_archive_with_new_link_and_revokes_old(): void
    {
        $this->actingAs($this->admin);
        Filament::setCurrentPanel('admin');
        $ahmad = $this->santri('Ahmad');
        $lama = $this->cetak(['status' => 'Aktif']);

        Livewire::test(ListLaporanArsips::class)
            ->callTableAction('cetakUlang', $lama->arsip, data: ['pin' => true, 'cabut_lama' => true])
            ->assertHasNoTableActionErrors()
            ->assertNotified();

        $baru = LaporanArsip::latest('id')->first();
        $this->assertNotSame($lama->arsip->id, $baru->id);
        $this->assertSame($lama->arsip->id, $baru->dicetak_ulang_dari);
        $this->assertSame(['status' => 'Aktif'], $baru->filters);
        $this->assertTrue($baru->aksesAktif());
        $this->assertTrue($baru->punyaPin());
        $this->assertNotSame($lama->arsip->akses_kunci_hash, $baru->akses_kunci_hash);
        $this->assertSame(LaporanArsip::AKSES_DICABUT, $lama->arsip->fresh()->statusAkses());
        $this->get(SantriPreviewLink::url($lama->kunciAkses, $ahmad->id))->assertNotFound();
    }

    public function test_riwayat_akses_lists_opened_links(): void
    {
        $this->actingAs($this->admin);
        Filament::setCurrentPanel('admin');
        $ahmad = $this->santri('Ahmad Riwayat');
        $generated = $this->cetak();
        $this->get(SantriPreviewLink::url($generated->kunciAkses, $ahmad->id))->assertOk();

        Livewire::test(ListLaporanArsips::class)
            ->assertTableActionVisible('riwayatAkses', $generated->arsip)
            ->mountTableAction('riwayatAkses', $generated->arsip)
            ->assertOk();

        $html = view('filament.laporan-arsip.riwayat-akses', [
            'logs' => $generated->arsip->aksesLogs()->with('dataSantri:id,nama')->get(),
            'total' => $generated->arsip->aksesLogs()->count(),
        ])->render();

        $this->assertStringContainsString('Ahmad Riwayat', $html);
        $this->assertStringContainsString('Total dibuka 1x', $html);
    }
}
