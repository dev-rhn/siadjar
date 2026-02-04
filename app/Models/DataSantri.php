<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSantri extends Model
{
    protected $table = 'data_santris';

    protected $fillable = [
        'nama',
        'nik',
        'no_kk',
        'jk',
        'tmp_lhr',
        'tgl_lhr',
        'alamat',
        'kel',
        'kec',
        'kab',
        'prov',
        'kode_pos',
        'asal_sekolah',
        'keterangan',
        'status',
        'tahun_masuk',
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
    ];
}
