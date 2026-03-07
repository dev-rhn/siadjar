<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'kd_kelas',
        'nama_kelas',
        'tingkat',
        'pegawai_id',
    ];

    public function waliKelas()
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }
}
