<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surats';

    protected $fillable = [
        'nama_jenis',
        'kode_angka',
    ];

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }
}
