<?php

namespace App\Filament\Resources\KategoriPelanggarans\Pages;

use App\Filament\Resources\KategoriPelanggarans\KategoriPelanggaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKategoriPelanggarans extends ListRecords
{
    protected static string $resource = KategoriPelanggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
