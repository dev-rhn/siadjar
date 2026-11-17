<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanArsip extends Model
{
    public const AKSES_TANPA_LINK = 'Tanpa link';

    public const AKSES_AKTIF = 'Aktif';

    public const AKSES_KEDALUWARSA = 'Kedaluwarsa';

    public const AKSES_DICABUT = 'Dicabut';

    protected $fillable = [
        'report_slug',
        'judul',
        'filters',
        'keterangan_filter',
        'orientasi',
        'jumlah_baris',
        'file_path',
        'file_size',
        'user_id',
        'akses_kunci_hash',
        'akses_berlaku_sampai',
        'akses_dicabut_at',
        'akses_pin_hash',
        'dicetak_ulang_dari',
    ];

    protected $hidden = [
        'akses_kunci_hash',
        'akses_pin_hash',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'jumlah_baris' => 'integer',
            'file_size' => 'integer',
            'akses_berlaku_sampai' => 'datetime',
            'akses_dicabut_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function aksesLogs(): HasMany
    {
        return $this->hasMany(LaporanAksesLog::class);
    }

    public function cetakUlangDari(): BelongsTo
    {
        return $this->belongsTo(self::class, 'dicetak_ulang_dari');
    }

    public function downloadFileName(): string
    {
        return 'laporan-'.$this->report_slug.'-'.$this->created_at->format('Ymd-Hi').'.pdf';
    }

    public function punyaLinkPreview(): bool
    {
        return $this->akses_kunci_hash !== null;
    }

    public function punyaPin(): bool
    {
        return $this->akses_pin_hash !== null;
    }

    public function aksesDicabut(): bool
    {
        return $this->akses_dicabut_at !== null;
    }

    public function aksesAktif(): bool
    {
        return $this->punyaLinkPreview()
            && ! $this->aksesDicabut()
            && $this->akses_berlaku_sampai?->isFuture();
    }

    public function statusAkses(): string
    {
        return match (true) {
            ! $this->punyaLinkPreview() => self::AKSES_TANPA_LINK,
            $this->aksesDicabut() => self::AKSES_DICABUT,
            $this->aksesAktif() => self::AKSES_AKTIF,
            default => self::AKSES_KEDALUWARSA,
        };
    }
}
