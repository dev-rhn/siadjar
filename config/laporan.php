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
    | Link Preview Santri
    |--------------------------------------------------------------------------
    |
    | Nama santri di PDF bisa diklik untuk membuka halaman preview tanpa login.
    | Akses dikendalikan per PDF (arsip): masa berlaku, cabut, dan PIN opsional.
    |
    */

    'link_preview' => [
        'masa_berlaku_hari' => (int) env('LAPORAN_LINK_MASA_BERLAKU_HARI', 30),
        'pin_sesi_menit' => 120,
        'pin_maks_percobaan' => 5,
        'pin_kunci_menit' => 15,
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
