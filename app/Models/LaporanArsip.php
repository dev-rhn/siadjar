<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanArsip extends Model
{
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
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'jumlah_baris' => 'integer',
            'file_size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function downloadFileName(): string
    {
        return 'laporan-'.$this->report_slug.'-'.$this->created_at->format('Ymd-Hi').'.pdf';
    }
}
