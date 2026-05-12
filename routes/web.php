<?php

use App\Http\Controllers\SantriPdfController;
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
Route::get('/santri/{santri}/preview-html', function (App\Models\DataSantri $santri) {
    $santri->load(['kesehatan', 'catatanPelanggarans.pelanggaran.kategoriPelanggaran', 'kelas', 'kamar']);
    return view('pdf.santri', [
        'santri' => $santri, 
        'fotoBase64' => null, 
        'lampirans' => [] 
    ]);
})->name('santri.preview')->middleware(['auth']);
