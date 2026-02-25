<?php

namespace App\Filament\Resources\CatatanPelanggarans\Pages;

use App\Filament\Resources\CatatanPelanggarans\CatatanPelanggaranResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCatatanPelanggaran extends ViewRecord
{
    protected static string $resource = CatatanPelanggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
