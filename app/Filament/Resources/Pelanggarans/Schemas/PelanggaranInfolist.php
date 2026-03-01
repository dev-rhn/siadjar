<?php

namespace App\Filament\Resources\Pelanggarans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PelanggaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kategori_pelanggaran_id')
                    ->numeric(),
                TextEntry::make('nama_pelanggaran'),
                TextEntry::make('poin')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
