<?php

use App\Reports\KesehatanReport;
use App\Reports\PelanggaranReport;
use App\Reports\SantriReport;

return [

    /*
    |--------------------------------------------------------------------------
    | Nama Lembaga
    |--------------------------------------------------------------------------
    |
    | Ditampilkan sebagai header di setiap laporan PDF.
    |
    */

    'nama_lembaga' => env('LAPORAN_NAMA_LEMBAGA', 'YAYASAN ANNY DJAMALUDDIN RASYAD'),

    /*
    |--------------------------------------------------------------------------
    | Batas Baris
    |--------------------------------------------------------------------------
    |
    | Jumlah baris maksimum per laporan. DomPDF lambat dan boros memori untuk
    | dataset besar, jadi user diminta mempersempit filter di atas batas ini.
    |
    */

    'max_baris' => (int) env('LAPORAN_MAX_BARIS', 2000),

    /*
    |--------------------------------------------------------------------------
    | Penyimpanan Arsip
    |--------------------------------------------------------------------------
    */

    'arsip_disk' => env('LAPORAN_ARSIP_DISK', 'local'),

    'arsip_directory' => 'laporan',

    /*
    |--------------------------------------------------------------------------
    | Ambang Poin Pelanggaran
    |--------------------------------------------------------------------------
    |
    | Total poin minimum per santri untuk setiap status sanksi.
    |
    */

    'ambang_poin' => [
        'SP1' => 25,
        'SP2' => 50,
        'SP3' => 75,
    ],

    /*
    |--------------------------------------------------------------------------
    | Daftar Laporan
    |--------------------------------------------------------------------------
    |
    | Setiap class harus turunan dari App\Reports\BaseReport.
    |
    */

    'reports' => [
        SantriReport::class,
        PelanggaranReport::class,
        KesehatanReport::class,
    ],

];
