<?php

namespace App\Filament\Resources\CatatanPelanggarans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CatatanPelanggaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('data_santri_id')
                    ->numeric(),
                TextEntry::make('pelanggaran_id')
                    ->numeric(),
                TextEntry::make('tanggal_kejadian')
                    ->date(),
                TextEntry::make('catatan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
