<?php

namespace App\Reports\Support;

/**
 * Menentukan status sanksi (SP) dari total poin pelanggaran berdasarkan config laporan.ambang_poin.
 */
class StatusPoin
{
    public const AMAN = 'Aman';

    /**
     * @return array<string, int> status => poin minimum, urut dari ambang tertinggi
     */
    public static function ambang(): array
    {
        $ambang = config('laporan.ambang_poin', []);
        arsort($ambang);

        return $ambang;
    }

    public static function minimumSp(): int
    {
        return min(static::ambang() ?: [PHP_INT_MAX]);
    }

    public static function for(int $totalPoin): string
    {
        foreach (static::ambang() as $status => $minimum) {
            if ($totalPoin >= $minimum) {
                return $status;
            }
        }

        return static::AMAN;
    }

    /**
     * Keterangan ambang untuk dicetak, mis. "SP1 ≥ 25, SP2 ≥ 50, SP3 ≥ 75".
     */
    public static function keterangan(): string
    {
        return collect(static::ambang())
            ->sort()
            ->map(fn (int $minimum, string $status) => "{$status} ≥ {$minimum}")
            ->implode(', ');
    }
}
