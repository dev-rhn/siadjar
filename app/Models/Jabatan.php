<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatans';

    protected $fillable = [
        'kd_jabatan',
        'nama_jabatan',
        'keterangan',
    ];

    public function pegawais()
    {
        return $this->hasMany(Kepegawaian::class);
    }
}
