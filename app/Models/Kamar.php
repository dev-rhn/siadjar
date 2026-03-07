<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamars';

    protected $fillable = [
        'kd_kamar',
        'nama_kamar',
        'kapasitas',
        'keterangan',
        'pegawai_id',
    ];

    public function pengasuh()
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }
}
