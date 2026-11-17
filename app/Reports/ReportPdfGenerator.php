<?php

namespace App\Reports;

use App\Models\LaporanArsip;
use App\Models\User;
use App\Reports\Support\SantriPreviewLink;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ReportPdfGenerator
{
    /**
     * Render laporan ke PDF lalu simpan ke arsip.
     *
     * Tanpa link preview: jika penyimpanan arsip gagal, PDF tetap dikembalikan dan error dicatat di log.
     * Dengan link preview: arsip wajib tersimpan (link bergantung padanya), jadi kegagalan dilempar.
     *
     * @param  array<string, mixed>  $filters  filter yang sudah divalidasi
     * @param  string|null  $pin  PIN akses (plain) jika link dilindungi PIN
     */
    public function generate(
        BaseReport $report,
        array $filters,
        string $orientation,
        ?User $user,
        bool $denganLink = false,
        ?string $pin = null,
        ?LaporanArsip $cetakUlangDari = null,
    ): GeneratedReport {
        $result = $report->build($filters);
        $printedAt = Carbon::now()->locale('id');

        $akses = $denganLink ? [
            'kunci' => SantriPreviewLink::buatKunci(),
            'berlaku_sampai' => $printedAt->copy()->addDays(SantriPreviewLink::masaBerlakuHari()),
            'pin' => $pin,
        ] : null;

        $pdf = Pdf::loadView($report->view(), [
            'result' => $result,
            'namaLembaga' => config('laporan.nama_lembaga'),
            'orientation' => $orientation,
            'linkFor' => $akses ? fn (int|string $santriId) => SantriPreviewLink::url($akses['kunci'], $santriId) : null,
            'aksesBerlakuSampai' => $akses ? $akses['berlaku_sampai']->translatedFormat('d F Y') : null,
            'aksesDenganPin' => $akses && $pin !== null,
        ])
            ->setPaper('a4', $orientation)
            // Tanpa subsetting, font DejaVu ter-embed utuh dan file membengkak ~900 KB.
            ->setOption('isFontSubsettingEnabled', true);

        $pdf->render();
        $this->addFooter($pdf->getDomPDF(), $printedAt, $user);

        $content = $pdf->output();
        $fileName = 'laporan-'.$report::slug().'-'.$printedAt->format('Ymd-Hi').'.pdf';

        return new GeneratedReport(
            content: $content,
            fileName: $fileName,
            arsip: $this->archive($report, $result, $orientation, $content, $printedAt, $user, $akses, $cetakUlangDari),
            kunciAkses: $akses['kunci'] ?? null,
        );
    }

    private function addFooter(Dompdf $dompdf, Carbon $printedAt, ?User $user): void
    {
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('DejaVu Sans');
        $fontSize = 7;
        $y = $canvas->get_height() - 24;

        $printedBy = 'Dicetak '.$printedAt->translatedFormat('d F Y H:i').($user ? ' oleh '.$user->name : '');
        $canvas->page_text(28, $y, $printedBy, $font, $fontSize, [0.4, 0.4, 0.4]);

        $pageText = 'Hal {PAGE_NUM} dari {PAGE_COUNT}';
        $pageTextWidth = $dompdf->getFontMetrics()->getTextWidth('Hal 99 dari 99', $font, $fontSize);
        $canvas->page_text($canvas->get_width() - 28 - $pageTextWidth, $y, $pageText, $font, $fontSize, [0.4, 0.4, 0.4]);
    }

    private function archive(
        BaseReport $report,
        ReportResult $result,
        string $orientation,
        string $content,
        Carbon $printedAt,
        ?User $user,
        ?array $akses,
        ?LaporanArsip $cetakUlangDari,
    ): ?LaporanArsip {
        $disk = config('laporan.arsip_disk');
        $path = null;

        try {
            $path = sprintf(
                '%s/%s/laporan-%s-%s-%s.pdf',
                config('laporan.arsip_directory'),
                $printedAt->format('Y/m'),
                $report::slug(),
                $printedAt->format('Ymd-His'),
                Str::lower(Str::random(6)),
            );

            if (! Storage::disk($disk)->put($path, $content)) {
                throw new RuntimeException("Gagal menulis file arsip ke disk [{$disk}].");
            }

            return LaporanArsip::create([
                'report_slug' => $report::slug(),
                'judul' => $report->title(),
                'filters' => $result->filters,
                'keterangan_filter' => $result->filterDescriptionText(),
                'orientasi' => $orientation,
                'jumlah_baris' => $result->rowCount(),
                'file_path' => $path,
                'file_size' => strlen($content),
                'user_id' => $user?->getKey(),
                'akses_kunci_hash' => $akses ? SantriPreviewLink::hashKunci($akses['kunci']) : null,
                'akses_berlaku_sampai' => $akses['berlaku_sampai'] ?? null,
                'akses_pin_hash' => isset($akses['pin']) ? Hash::make($akses['pin']) : null,
                'dicetak_ulang_dari' => $cetakUlangDari?->getKey(),
            ]);
        } catch (Throwable $exception) {
            Log::error('Gagal mengarsipkan laporan PDF.', [
                'report' => $report::slug(),
                'path' => $path,
                'exception' => $exception,
            ]);

            if ($path) {
                rescue(fn () => Storage::disk($disk)->delete($path), report: false);
            }

            if ($akses) {
                throw new RuntimeException('Gagal mengarsipkan laporan dengan link preview.', previous: $exception);
            }

            return null;
        }
    }
}
