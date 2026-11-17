<?php

namespace App\Http\Controllers;

use App\Models\LaporanArsip;
use App\Reports\BaseReport;
use App\Reports\ReportPdfGenerator;
use App\Reports\ReportRegistry;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

class ReportPdfController extends Controller
{
    public function generate(Request $request, string $slug, ReportPdfGenerator $generator): Response
    {
        $this->ensurePanelAccess($request);

        $report = ReportRegistry::findOrFail($slug);

        $options = $request->validate([
            'orientasi' => ['nullable', Rule::in(array_keys(BaseReport::ORIENTATIONS))],
            'unduh' => ['nullable', 'boolean'],
        ]);

        $input = $request->input('filters', []);
        $filters = $report->validateFilters(is_array($input) ? $input : []);

        $maxRows = (int) config('laporan.max_baris');
        $rowCount = $report->count($filters);

        abort_if(
            $rowCount > $maxRows,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            "Jumlah data ({$rowCount}) melebihi batas {$maxRows} baris. Persempit filter laporan.",
        );

        $generated = $generator->generate(
            $report,
            $filters,
            $options['orientasi'] ?? $report->defaultOrientation(),
            $request->user(),
        );

        return response($generated->content, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => HeaderUtils::makeDisposition(
                $request->boolean('unduh') ? HeaderUtils::DISPOSITION_ATTACHMENT : HeaderUtils::DISPOSITION_INLINE,
                $generated->fileName,
            ),
        ]);
    }

    public function downloadArsip(Request $request, LaporanArsip $laporanArsip): Response
    {
        $this->ensurePanelAccess($request);

        $disk = Storage::disk(config('laporan.arsip_disk'));

        abort_unless($disk->exists($laporanArsip->file_path), Response::HTTP_NOT_FOUND, 'File arsip tidak ditemukan.');

        $disposition = $request->boolean('lihat') ? HeaderUtils::DISPOSITION_INLINE : HeaderUtils::DISPOSITION_ATTACHMENT;

        return $disk->response($laporanArsip->file_path, $laporanArsip->downloadFileName(), [
            'Content-Type' => 'application/pdf',
        ], $disposition);
    }

    private function ensurePanelAccess(Request $request): void
    {
        abort_unless(
            $request->user()?->canAccessPanel(Filament::getPanel('admin')),
            Response::HTTP_FORBIDDEN,
        );
    }
}
