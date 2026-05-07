<?php

namespace App\Filament\Exports;

use App\Models\Pelanggaran;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PelanggaranExporter extends Exporter
{
    protected static ?string $model = Pelanggaran::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('kategoriPelanggaran.nama_kategori')
                ->label('Kategori Pelanggaran'),
            ExportColumn::make('nama_pelanggaran')
                ->label('Nama Pelanggaran'),
            ExportColumn::make('poin')
                ->label('Poin'),
            ExportColumn::make('created_at')
                ->label('Dibuat Pada'),
            ExportColumn::make('updated_at')
                ->label('Diperbarui Pada'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pelanggaran export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
