<?php

namespace App\Filament\Exports;

use App\Models\Kelas;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class KelasExporter extends Exporter
{
    protected static ?string $model = Kelas::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('kd_kelas')
                ->label('Kode Kelas'),
            ExportColumn::make('nama_kelas')
                ->label('Nama Kelas'),
            ExportColumn::make('tingkat')
                ->label('Tingkat'),
            ExportColumn::make('waliKelas.nama_pegawai')
                ->label('Nama Wali Kelas'),
            ExportColumn::make('created_at')
                ->label('Dibuat Pada'),
            ExportColumn::make('updated_at')
                ->label('Diperbarui Pada'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your kelas export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
