<?php

namespace App\Filament\Resources\CatatanPelanggarans\Pages;

use App\Filament\Resources\CatatanPelanggarans\CatatanPelanggaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCatatanPelanggarans extends ListRecords
{
    protected static string $resource = CatatanPelanggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
