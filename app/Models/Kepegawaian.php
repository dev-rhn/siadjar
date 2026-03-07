<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kepegawaian extends Model
{
    protected $fillable = [
        'user_id', 
        'kd_pegawai',
        'jabatan_id',
        'nama_pegawai',
        'jabatan', 
        'no_telepon', 
        'alamat',
        'status_kepegawaian', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function kamar()
    {
        return $this->hasMany(Kamar::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
