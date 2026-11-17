<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanAksesLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'laporan_arsip_id',
        'data_santri_id',
        'ip',
        'user_agent',
    ];

    public function arsip(): BelongsTo
    {
        return $this->belongsTo(LaporanArsip::class, 'laporan_arsip_id');
    }

    public function dataSantri(): BelongsTo
    {
        return $this->belongsTo(DataSantri::class, 'data_santri_id');
    }
}
