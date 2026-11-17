<?php

namespace App\Http\Controllers;

use App\Models\DataSantri;
use App\Models\LaporanAksesLog;
use App\Models\LaporanArsip;
use App\Reports\Support\SantriPreviewLink;
use App\Reports\Support\StatusPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Halaman preview santri tanpa login, dibuka dari link di PDF laporan.
 */
class SantriPreviewController extends Controller
{
    public function show(Request $request, string $kunci, string $santri, string $tandaTangan): Response
    {
        [$arsip, $dataSantri] = $this->resolve($kunci, $santri, $tandaTangan);

        if (! $arsip) {
            return $this->tidakBerlaku();
        }

        if ($arsip->punyaPin() && ! $this->pinTerverifikasi($request, $arsip)) {
            return $this->privat(response()->view('preview.pin', [
                'namaLembaga' => config('laporan.nama_lembaga'),
                'terkunci' => RateLimiter::tooManyAttempts($this->pinLimiterKey($request, $arsip), $this->maksPercobaan()),
                'tungguMenit' => (int) ceil(RateLimiter::availableIn($this->pinLimiterKey($request, $arsip)) / 60),
            ]));
        }

        LaporanAksesLog::create([
            'laporan_arsip_id' => $arsip->id,
            'data_santri_id' => $dataSantri->id,
            'ip' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);

        return $this->privat(response()->view('preview.santri', [
            'namaLembaga' => config('laporan.nama_lembaga'),
            'santri' => $dataSantri,
            'fotoBase64' => $this->fotoBase64($dataSantri->foto_santri),
            'arsip' => $arsip,
            ...$this->dataPelanggaran($dataSantri, $arsip),
            'kesehatans' => $dataSantri->kesehatan()->orderByDesc('tanggal_sakit')->orderByDesc('id')->get(),
            'diaksesPada' => now()->locale('id'),
        ]));
    }

    public function submitPin(Request $request, string $kunci, string $santri, string $tandaTangan): Response
    {
        [$arsip] = $this->resolve($kunci, $santri, $tandaTangan);

        if (! $arsip || ! $arsip->punyaPin()) {
            return $this->tidakBerlaku();
        }

        $limiterKey = $this->pinLimiterKey($request, $arsip);

        if (RateLimiter::tooManyAttempts($limiterKey, $this->maksPercobaan())) {
            return back();
        }

        $pin = (string) $request->input('pin', '');

        if (! Hash::check($pin, $arsip->akses_pin_hash)) {
            RateLimiter::hit($limiterKey, (int) config('laporan.link_preview.pin_kunci_menit', 15) * 60);

            return back()->withErrors(['pin' => 'PIN salah.']);
        }

        RateLimiter::clear($limiterKey);
        $request->session()->regenerate();
        $request->session()->put(
            $this->pinSessionKey($arsip),
            now()->addMinutes((int) config('laporan.link_preview.pin_sesi_menit', 120))->getTimestamp(),
        );

        return redirect()->route('santri-preview.show', compact('kunci', 'santri', 'tandaTangan'));
    }

    /**
     * @return array{0: LaporanArsip|null, 1: DataSantri|null}
     */
    private function resolve(string $kunci, string $santri, string $tandaTangan): array
    {
        $arsip = SantriPreviewLink::resolve($kunci, $santri, $tandaTangan);
        $dataSantri = $arsip ? DataSantri::with(['kelas', 'kamar'])->find($santri) : null;

        return $dataSantri ? [$arsip, $dataSantri] : [null, null];
    }

    /**
     * Semua riwayat pelanggaran; total poin & status SP dihitung untuk periode laporan
     * (jika PDF memakai periode), ditambah total keseluruhan sebagai informasi.
     *
     * @return array<string, mixed>
     */
    private function dataPelanggaran(DataSantri $santri, LaporanArsip $arsip): array
    {
        $pelanggarans = $santri->catatanPelanggarans()
            ->with('pelanggaran.kategoriPelanggaran')
            ->orderByDesc('tanggal_kejadian')
            ->orderByDesc('id')
            ->get();

        $poin = fn ($items) => (int) $items->sum(fn ($catatan) => $catatan->pelanggaran?->poin ?? 0);

        $dari = $arsip->filters['tanggal_dari'] ?? null;
        $sampai = $arsip->filters['tanggal_sampai'] ?? null;
        $dalamPeriode = fn ($catatan) => $dari && $sampai
            && Carbon::parse($catatan->tanggal_kejadian)->betweenIncluded(Carbon::parse($dari), Carbon::parse($sampai));

        $totalKeseluruhan = $poin($pelanggarans);
        $totalPeriode = $dari && $sampai ? $poin($pelanggarans->filter($dalamPeriode)) : null;

        return [
            'pelanggarans' => $pelanggarans,
            'dalamPeriode' => $dalamPeriode,
            'periode' => $dari && $sampai ? [Carbon::parse($dari)->locale('id'), Carbon::parse($sampai)->locale('id')] : null,
            'totalPoinPeriode' => $totalPeriode,
            'totalPoinKeseluruhan' => $totalKeseluruhan,
            'statusSp' => StatusPoin::for($totalPeriode ?? $totalKeseluruhan),
            'keteranganAmbang' => StatusPoin::keterangan(),
        ];
    }

    private function pinTerverifikasi(Request $request, LaporanArsip $arsip): bool
    {
        return (int) $request->session()->get($this->pinSessionKey($arsip), 0) > now()->getTimestamp();
    }

    private function pinSessionKey(LaporanArsip $arsip): string
    {
        return 'santri_preview.pin.'.$arsip->id;
    }

    private function pinLimiterKey(Request $request, LaporanArsip $arsip): string
    {
        return 'santri-preview-pin:'.$arsip->id.':'.$request->ip();
    }

    private function maksPercobaan(): int
    {
        return (int) config('laporan.link_preview.pin_maks_percobaan', 5);
    }

    private function fotoBase64(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $disk = Storage::disk('public');

        return 'data:'.$disk->mimeType($path).';base64,'.base64_encode($disk->get($path));
    }

    private function tidakBerlaku(): Response
    {
        return $this->privat(response()->view('preview.tidak-berlaku', [
            'namaLembaga' => config('laporan.nama_lembaga'),
        ], Response::HTTP_NOT_FOUND));
    }

    private function privat(Response $response): Response
    {
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        $response->headers->set('Cache-Control', 'no-store, private');
        $response->headers->set('Referrer-Policy', 'no-referrer');

        return $response;
    }
}
