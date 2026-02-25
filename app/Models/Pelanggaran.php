<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = 'pelanggarans';

    protected $fillable = [
        'kategori_pelanggaran_id',
        'nama_pelanggaran',
        'poin',
    ];

    public function kategoriPelanggaran()
    {
        return $this->belongsTo(KategoriPelanggaran::class, 'kategori_pelanggaran_id');
    }

    public function catatanPelanggarans()
    {
        return $this->hasMany(CatatanPelanggaran::class, 'pelanggaran_id');
    }
}
