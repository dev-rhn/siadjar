<?php

namespace App\Filament\Exports;

use App\Models\Kepegawaian;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class KepegawaianExporter extends Exporter
{
    protected static ?string $model = Kepegawaian::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('user_id'),
            ExportColumn::make('jabatan_id'),
            ExportColumn::make('kd_pegawai'),
            ExportColumn::make('nama_pegawai'),
            ExportColumn::make('no_telepon'),
            ExportColumn::make('alamat'),
            ExportColumn::make('status_kepegawaian'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your kepegawaian export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
