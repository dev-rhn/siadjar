<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kesehatan extends Model
{
    protected $fillable = [
        'data_santri_id',
        'nama_penyakit',
        'obat',
        'tanggal_sakit',
        'keterangan',
        'is_sembuh',
    ];

    public function dataSantri()
    {
        return $this->belongsTo(DataSantri::class, 'data_santri_id');
    }
}
