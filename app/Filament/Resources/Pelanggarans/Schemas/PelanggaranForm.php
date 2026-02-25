<?php

namespace App\Filament\Resources\Pelanggarans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PelanggaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori_pelanggaran_id')
                    ->label('Kategori Pelanggaran')
                    ->relationship('kategoriPelanggaran', 'nama_kategori')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('nama_pelanggaran')
                    ->label('Nama Pelanggaran')
                    ->placeholder('Masukkan Nama Pelanggaran')
                    ->required(),
                TextInput::make('poin')
                    ->label('Poin Pelanggaran')
                    ->placeholder('Masukkan Poin Pelanggaran')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
