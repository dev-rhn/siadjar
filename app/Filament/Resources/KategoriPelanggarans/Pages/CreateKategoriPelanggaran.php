<?php

namespace App\Filament\Resources\KategoriPelanggarans\Pages;

use App\Filament\Resources\KategoriPelanggarans\KategoriPelanggaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKategoriPelanggaran extends CreateRecord
{
    protected static string $resource = KategoriPelanggaranResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
