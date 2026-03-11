<?php

use App\Models\DataSantri;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::get('/santri/{santri}/pdf', function (DataSantri $santri) {
    $santri->load([
        'kesehatan',
        'catatanPelanggarans.pelanggaran.kategoriPelanggaran'
    ]);
    return view('pdf.santri', [
        'santri'     => $santri,
    ]);
})->name('santri.pdf')->middleware(['auth']);
