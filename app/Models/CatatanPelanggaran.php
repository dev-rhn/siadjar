<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanPelanggaran extends Model
{
    protected $table = 'catatan_pelanggarans';

    protected $fillable = [
        'data_santri_id',
        'pelanggaran_id',
        'tanggal_kejadian',
        'catatan',
    ];

    public function dataSantri()
    {
        return $this->belongsTo(DataSantri::class,'data_santri_id');
    }

    public function pelanggaran()
    {
        return $this->belongsTo(Pelanggaran::class,'pelanggaran_id');
    }
}
