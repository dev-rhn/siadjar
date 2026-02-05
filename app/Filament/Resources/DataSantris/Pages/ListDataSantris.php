<?php

namespace App\Filament\Resources\DataSantris\Pages;

use App\Filament\Resources\DataSantris\DataSantriResource;
use App\Filament\Resources\DataSantris\Widgets\DataSantriStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataSantris extends ListRecords
{
    protected static string $resource = DataSantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DataSantriStats::class,
        ];
    }
}
