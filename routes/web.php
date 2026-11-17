<?php

use App\Http\Controllers\ReportPdfController;
use App\Http\Controllers\SantriPdfController;
use App\Http\Controllers\SantriPreviewController;
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

// Preview santri tanpa login dari link di PDF laporan (akses dikendalikan per arsip)
Route::prefix('p/{kunci}/{santri}/{tandaTangan}')
    ->where(['kunci' => '[A-Za-z0-9]{40}', 'santri' => '[0-9]+', 'tandaTangan' => '[a-f0-9]{32}'])
    ->middleware('throttle:60,1')
    ->name('santri-preview.')
    ->group(function () {
        Route::get('/', [SantriPreviewController::class, 'show'])->name('show');
        Route::post('/', [SantriPreviewController::class, 'submitPin'])->name('pin');
    });
