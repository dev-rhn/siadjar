<?php

namespace App\Filament\Resources\Kepegawaians\Pages;

use App\Filament\Resources\Kepegawaians\KepegawaianResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKepegawaian extends CreateRecord
{
    protected static string $resource = KepegawaianResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
