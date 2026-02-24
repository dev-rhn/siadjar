<?php

namespace App\Filament\Resources\Kepegawaians\Pages;

use App\Filament\Resources\Kepegawaians\KepegawaianResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKepegawaian extends EditRecord
{
    protected static string $resource = KepegawaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
