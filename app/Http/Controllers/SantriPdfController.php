<?php

namespace App\Http\Controllers;

use App\Models\DataSantri;
use Barryvdh\DomPDF\Facade\Pdf;

class SantriPdfController extends Controller
{
    public function viewPdf(DataSantri $santri)
    {
        $santri->load(['kesehatan', 'catatanPelanggarans.pelanggaran.kategoriPelanggaran']);

        $fotoBase64 = $this->convertToBase64($santri->foto_santri);

        $attachmentFields = [
            'foto_kk' => 'Kartu Keluarga',
            'foto_akte' => 'Akte Kelahiran',
            'ijazah' => 'Ijazah Terakhir',
            'nilai_rapot' => 'Nilai Rapot',
            'surat_ket_pindah_sekolah' => 'Surat Pindah',
            'surat_ket_dhuafa' => 'Keterangan Dhuafa',
            'surat_kematian_org_tua' => 'Surat Kematian',
            'surat_ket_hak_asuh' => 'Hak Asuh'
        ];

        $lampirans = [];
        foreach ($attachmentFields as $field => $label) {
            if ($santri->$field) {
                $base64 = $this->convertToBase64($santri->$field);
                if ($base64) {
                    $lampirans[] = [
                        'label' => $label,
                        'data'  => $base64
                    ];
                }
            }
        }

        $pdf = Pdf::loadView('pdf.santri', [
            'santri'     => $santri,
            'fotoBase64' => $fotoBase64,
            'lampirans'  => $lampirans 
        ])->setPaper('a4', 'portrait');

        $fileName = 'santri-' . $santri->nisn . '.pdf';
        return $pdf->stream($fileName);
    }

    // Helper function agar kode tetap rapi
    private function convertToBase64($path)
    {
        if (!$path) return null;
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) return null;

        $type = mime_content_type($fullPath);
        $data = file_get_contents($fullPath);
        return 'data:' . $type . ';base64,' . base64_encode($data);
    }
}
