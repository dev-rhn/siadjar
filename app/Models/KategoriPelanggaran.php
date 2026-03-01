<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPelanggaran extends Model
{
    protected $table = 'kategori_pelanggarans';

    protected $fillable = [
        'nama_kategori',
        'keterangan',
    ];

    public function pelanggarans()
    {
        return $this->hasMany(Pelanggaran::class);
    }
}
