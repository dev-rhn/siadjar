<?php

namespace App\Reports\Support;

use App\Models\LaporanArsip;
use Illuminate\Support\Str;

/**
 * Link preview santri tanpa login yang ditanam di PDF laporan.
 *
 * Format: /p/{kunci}/{data_santri_id}/{tanda_tangan}
 * - kunci: acak per PDF, yang disimpan di arsip hanya hash SHA-256-nya.
 * - tanda_tangan: HMAC atas kunci + id santri, sehingga id di URL tidak bisa diganti
 *   untuk membuka santri lain.
 */
class SantriPreviewLink
{
    public const PANJANG_KUNCI = 40;

    public const PANJANG_TANDA_TANGAN = 32;

    public const PANJANG_TOKEN_PIN = 32;

    /**
     * Prefix session untuk PIN yang dibuat di halaman Cetak Laporan dan dipakai sekali oleh route PDF.
     */
    public const PIN_SESSION_PREFIX = 'laporan.pin_tertunda.';

    /**
     * Titipkan PIN di session dan kembalikan token sekali pakai untuk URL PDF.
     */
    public static function titipkanPin(string $pin): string
    {
        $token = Str::random(self::PANJANG_TOKEN_PIN);
        session()->put(self::PIN_SESSION_PREFIX.$token, $pin);

        return $token;
    }

    public static function buatKunci(): string
    {
        return Str::random(self::PANJANG_KUNCI);
    }

    public static function buatPin(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public static function hashKunci(string $kunci): string
    {
        return hash('sha256', $kunci);
    }

    public static function tandaTangan(string $kunci, int|string $santriId): string
    {
        return substr(
            hash_hmac('sha256', $kunci.'|'.$santriId, (string) config('app.key')),
            0,
            self::PANJANG_TANDA_TANGAN,
        );
    }

    public static function url(string $kunci, int|string $santriId): string
    {
        return route('santri-preview.show', [
            'kunci' => $kunci,
            'santri' => $santriId,
            'tandaTangan' => static::tandaTangan($kunci, $santriId),
        ]);
    }

    /**
     * Cari arsip yang aksesnya masih aktif untuk link ini. Null jika link tidak valid,
     * kedaluwarsa, atau dicabut — sengaja tidak dibedakan.
     */
    public static function resolve(string $kunci, int|string $santriId, string $tandaTangan): ?LaporanArsip
    {
        if (! hash_equals(static::tandaTangan($kunci, $santriId), $tandaTangan)) {
            return null;
        }

        $arsip = LaporanArsip::query()
            ->where('akses_kunci_hash', static::hashKunci($kunci))
            ->first();

        return $arsip?->aksesAktif() ? $arsip : null;
    }

    public static function masaBerlakuHari(): int
    {
        return (int) config('laporan.link_preview.masa_berlaku_hari', 30);
    }
}
