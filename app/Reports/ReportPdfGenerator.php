<?php

namespace App\Reports;

use App\Models\LaporanArsip;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ReportPdfGenerator
{
    /**
     * Render laporan ke PDF lalu simpan ke arsip.
     *
     * Jika penyimpanan arsip gagal, PDF tetap dikembalikan dan error dicatat di log.
     *
     * @param  array<string, mixed>  $filters  filter yang sudah divalidasi
     */
    public function generate(BaseReport $report, array $filters, string $orientation, ?User $user): GeneratedReport
    {
        $result = $report->build($filters);
        $printedAt = Carbon::now()->locale('id');

        $pdf = Pdf::loadView($report->view(), [
            'result' => $result,
            'namaLembaga' => config('laporan.nama_lembaga'),
            'orientation' => $orientation,
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
            arsip: $this->archive($report, $result, $orientation, $content, $printedAt, $user),
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
                throw new \RuntimeException("Gagal menulis file arsip ke disk [{$disk}].");
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

            return null;
        }
    }
}
