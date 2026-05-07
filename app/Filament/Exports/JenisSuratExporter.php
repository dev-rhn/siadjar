<?php

namespace App\Filament\Exports;

use App\Models\JenisSurat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class JenisSuratExporter extends Exporter
{
    protected static ?string $model = JenisSurat::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('nama_jenis')
                ->label('Nama Jenis'),
            ExportColumn::make('kode_angka')
                ->label('Kode Angka'),
            ExportColumn::make('keterangan')
                ->label('Keterangan'),
            ExportColumn::make('created_at')
                ->label('Dibuat Pada'),
            ExportColumn::make('updated_at')
                ->label('Diperbarui Pada'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your jenis surat export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
