<?php

namespace App\Filament\Resources\DataSantris\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DataSantriInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('jk')
                //     ->label('Jenis Kelamin'),
            ]);
    }
}
