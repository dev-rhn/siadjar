<?php

use App\Models\DataSantri;
use Barryvdh\DomPDF\Facade\Pdf;
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

Route::get('/santri/{santri}/view-pdf', function (DataSantri $santri) {
    $santri->load([
        'kesehatan',
        'catatanPelanggarans.pelanggaran.kategoriPelanggaran'
    ]);

    // Convert foto ke base64
    $fotoBase64 = null;
    if ($santri->foto_santri) {
        $fullPath = storage_path('app/public/' . $santri->foto_santri);
        if (file_exists($fullPath)) {
            $fotoMime = mime_content_type($fullPath);
            $fotoBase64 = 'data:' . $fotoMime . ';base64,' . base64_encode(file_get_contents($fullPath));
        }
    }

    $pdf = Pdf::loadView('pdf.santri', [
        'santri'     => $santri,
        'fotoBase64' => $fotoBase64,
    ])->setPaper('a4', 'portrait');

    return response()->make(
        $pdf->output(),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="santri-' . $santri->nisn . '-' . $santri->nama . '.pdf"'
        ]
    );
})->name('santri.view-pdf')->middleware(['auth']);
