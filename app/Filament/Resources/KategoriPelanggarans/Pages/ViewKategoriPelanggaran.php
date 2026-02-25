<?php

namespace App\Filament\Resources\KategoriPelanggarans\Pages;

use App\Filament\Resources\KategoriPelanggarans\KategoriPelanggaranResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKategoriPelanggaran extends ViewRecord
{
    protected static string $resource = KategoriPelanggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
