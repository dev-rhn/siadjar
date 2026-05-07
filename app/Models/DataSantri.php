<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSantri extends Model
{
    protected $table = 'data_santris';

    protected $fillable = [
        'no_kk',
        'nik',
        'nama',
        'nisn',
        'tmp_lhr',
        'tgl_lhr',
        'jk',
        'alamat',
        'rt',
        'rw',
        'kel',
        'kec',
        'kab',
        'prov',
        'kode_pos',
        'asal_sekolah',
        'keterangan',
        'status',
        'tahun_masuk',
        'kamar_id',
        'kelas_id',
        'jenjang',
        'nik_ayah',
        'nama_ayah',
        'tmp_lhr_ayah',
        'tgl_lhr_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'nik_ibu',
        'nama_ibu',
        'tmp_lhr_ibu',
        'tgl_lhr_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'foto_santri',
        'foto_kk',
    ];

    public function kesehatan()
    {
        return $this->hasMany(Kesehatan::class, "data_santri_id");
    }

    public function catatanPelanggarans()
    {
        return $this->hasMany(CatatanPelanggaran::class, "data_santri_id");
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
