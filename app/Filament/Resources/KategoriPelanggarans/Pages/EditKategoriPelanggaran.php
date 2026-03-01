<?php

namespace App\Filament\Resources\KategoriPelanggarans\Pages;

use App\Filament\Resources\KategoriPelanggarans\KategoriPelanggaranResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKategoriPelanggaran extends EditRecord
{
    protected static string $resource = KategoriPelanggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
