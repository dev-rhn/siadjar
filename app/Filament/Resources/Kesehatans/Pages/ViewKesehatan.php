<?php

namespace App\Filament\Resources\Kesehatans\Pages;

use App\Filament\Resources\Kesehatans\KesehatanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKesehatan extends ViewRecord
{
    protected static string $resource = KesehatanResource::class;

    public function getTitle(): string 
    {
        return "Detail Kesehatan: " . $this->record->dataSantri->nama;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
