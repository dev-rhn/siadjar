<?php

namespace App\Filament\Exports;

use App\Models\Kamar;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class KamarExporter extends Exporter
{
    protected static ?string $model = Kamar::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('kd_kamar')
                ->label('Kode Kamar'),
            ExportColumn::make('nama_kamar')
                ->label('Nama Kamar'),
            ExportColumn::make('kapasitas')
                ->label('Kapasitas'),
            ExportColumn::make('keterangan')
                ->label('Keterangan'),
            ExportColumn::make('pengasuh.nama_pegawai')
                ->label('Nama Pengasuh'),
            ExportColumn::make('created_at')
                ->label('Dibuat Pada'),
            ExportColumn::make('updated_at')
                ->label('Diperbarui Pada'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your kamar export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
