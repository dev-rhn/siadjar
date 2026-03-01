<?php

namespace App\Filament\Resources\JenisSurats\Pages;

use App\Filament\Resources\JenisSurats\JenisSuratResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJenisSurat extends ViewRecord
{
    protected static string $resource = JenisSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
