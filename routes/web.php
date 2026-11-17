<?php

use App\Http\Controllers\ReportPdfController;
use App\Http\Controllers\SantriPdfController;
use App\Models\DataSantri;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/admin/login');
});

// Route untuk generate dan view PDF santri
Route::get('/santri/{santri}/view-pdf', [SantriPdfController::class, 'viewPdf'])
    ->name('santri.view-pdf')
    ->middleware(['auth']);

// Route untuk cek layout HTML di browser
Route::get('/santri/{santri}/preview-html', function (DataSantri $santri) {
    $santri->load(['kesehatan', 'catatanPelanggarans.pelanggaran.kategoriPelanggaran', 'kelas', 'kamar']);

    return view('pdf.santri', [
        'santri' => $santri,
        'fotoBase64' => null,
        'lampirans' => [],
    ]);
})->name('santri.preview')->middleware(['auth']);

// Route laporan umum (PDF) dan arsipnya
Route::middleware(['auth'])->prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/arsip/{laporanArsip}/unduh', [ReportPdfController::class, 'downloadArsip'])
        ->name('arsip.unduh');

    Route::get('/{slug}/pdf', [ReportPdfController::class, 'generate'])
        ->name('pdf');
});
