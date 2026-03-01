<?php

namespace App\Filament\Resources\DataSantris\Pages;

use App\Filament\Resources\DataSantris\DataSantriResource;
use App\Filament\Resources\DataSantris\RelationManagers\CatatanPelanggaransRelationManager;
use App\Filament\Resources\DataSantris\RelationManagers\KesehatanRelationManager;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDataSantri extends ViewRecord
{
    protected static string $resource = DataSantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            CatatanPelanggaransRelationManager::class,
            KesehatanRelationManager::class,
        ];
    }
}
