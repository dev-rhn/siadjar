<?php

namespace App\Filament\Exports;

use App\Models\Kesehatan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class KesehatanExporter extends Exporter
{
    protected static ?string $model = Kesehatan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('dataSantri.nama_santri')
                ->label('Nama Santri'),
            ExportColumn::make('nama_penyakit')
                ->label('Nama Penyakit'),
            ExportColumn::make('obat')
                ->label('Obat'),
            ExportColumn::make('tanggal_sakit')
                ->label('Tanggal Sakit'),
            ExportColumn::make('keterangan')
                ->label('Keterangan'),
            ExportColumn::make('is_sembuh')
                ->label('Sembuh'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your kesehatan export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
