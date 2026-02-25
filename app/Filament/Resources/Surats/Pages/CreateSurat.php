<?php

namespace App\Filament\Resources\Surats\Pages;

use App\Filament\Resources\Surats\SuratResource;
use App\Models\Surat;
use Filament\Resources\Pages\CreateRecord;

class CreateSurat extends CreateRecord
{
    protected static string $resource = SuratResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate nomor surat saat disimpan
        $data['nomor_surat'] = Surat::generateNomor(
            $data['arah'],
            $data['jenis_surat_id'],
            $data['tanggal']
        );

        return $data;
    }
}
