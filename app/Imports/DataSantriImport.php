<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\DataSantri;

class DataSantriImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new DataSantri([
            'no_kk' => $row['no_kk'],
            'nik' => $row['nik'],
            'nama' => $row['nama'],
            'nisn' => $row['nisn'],
            'tmp_lhr' => $row['tmp_lhr'],
            'tgl_lhr' => $row['tgl_lhr'],
            'jk' => $row['jk'],
            'alamat' => $row['alamat'],
            'rt' => $row['rt'],
            'rw' => $row['rw'],
            'kel' => $row['kel'],
            'kec' => $row['kec'],
            'kab' => $row['kab'],
            'prov' => $row['prov'],
            'kode_pos' => $row['kode_pos'],
            'asal_sekolah' => $row['asal_sekolah'],
            'keterangan' => $row['keterangan'],
            'status' => $row['status'],
            'tahun_masuk' => $row['tahun_masuk'],
            'jenjang' => $row['jenjang'],
            'nik_ayah' => $row['nik_ayah'],
            'nama_ayah' => $row['nama_ayah'],
            'tmp_lhr_ayah' => $row['tmp_lhr_ayah'],
            'tgl_lhr_ayah' => $row['tgl_lhr_ayah'],
            'pendidikan_ayah' => $row['pendidikan_ayah'],
            'pekerjaan_ayah' => $row['pekerjaan_ayah'],
            'nik_ibu' => $row['nik_ibu'],
            'nama_ibu' => $row['nama_ibu'],
            'tmp_lhr_ibu' => $row['tmp_lhr_ibu'],
            'tgl_lhr_ibu' => $row['tgl_lhr_ibu'],
            'pendidikan_ibu' => $row['pendidikan_ibu'],
            'pekerjaan_ibu' => $row['pekerjaan_ibu'],
        ]);
    }
}
