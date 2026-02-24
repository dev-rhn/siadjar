<?php

namespace App\Filament\Resources\Kepegawaians\Pages;

use App\Filament\Resources\Kepegawaians\KepegawaianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKepegawaians extends ListRecords
{
    protected static string $resource = KepegawaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
