<?php

namespace App\Filament\Resources\LaporanArsips\Pages;

use App\Filament\Pages\Laporan;
use App\Filament\Resources\LaporanArsips\LaporanArsipResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListLaporanArsips extends ListRecords
{
    protected static string $resource = LaporanArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetakLaporan')
                ->label('Cetak Laporan Baru')
                ->icon(Heroicon::OutlinedPrinter)
                ->url(Laporan::getUrl()),
        ];
    }
}
