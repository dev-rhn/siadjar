<?php

namespace App\Filament\Resources\CatatanPelanggarans\Pages;

use App\Filament\Resources\CatatanPelanggarans\CatatanPelanggaranResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCatatanPelanggaran extends EditRecord
{
    protected static string $resource = CatatanPelanggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
