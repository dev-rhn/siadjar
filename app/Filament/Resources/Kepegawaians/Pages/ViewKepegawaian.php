<?php

namespace App\Filament\Resources\Kepegawaians\Pages;

use App\Filament\Resources\Kepegawaians\KepegawaianResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKepegawaian extends ViewRecord
{
    protected static string $resource = KepegawaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
