<?php

namespace App\Filament\Resources\DataSantris\Pages;

use App\Filament\Resources\DataSantris\DataSantriResource;
use App\Filament\Resources\DataSantris\RelationManagers\CatatanPelanggaransRelationManager;
use App\Filament\Resources\DataSantris\RelationManagers\KesehatanRelationManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDataSantri extends ViewRecord
{
    protected static string $resource = DataSantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download_pdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function ($record) {
                    $record->load([
                        'kesehatan',
                        'catatanPelanggarans.pelanggaran.kategoriPelanggaran'
                    ]);

                    // Convert foto ke base64
                    $fotoBase64 = null;
                    if ($record->foto_santri) {
                        $fullPath = storage_path('app/public/' . $record->foto_santri);
                        if (file_exists($fullPath)) {
                            $fotoMime = mime_content_type($fullPath);
                            $fotoBase64 = 'data:' . $fotoMime . ';base64,' . base64_encode(file_get_contents($fullPath));
                        }
                    }

                    $pdf = Pdf::loadView('pdf.santri', [
                        'santri'     => $record,
                        'fotoBase64' => $fotoBase64,
                    ])->setPaper('a4', 'portrait');

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        "Santri-{$record->nisn}-{$record->nama}.pdf"
                    );
                }),
            EditAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            CatatanPelanggaransRelationManager::class,
            KesehatanRelationManager::class,
        ];
    }
}
