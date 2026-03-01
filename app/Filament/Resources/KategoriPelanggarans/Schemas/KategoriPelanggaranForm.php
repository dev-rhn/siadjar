<?php

namespace App\Filament\Resources\KategoriPelanggarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KategoriPelanggaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_kategori')
                    ->label('Nama Kategori Pelanggaran')
                    ->placeholder('Masukkan Nama Kategori Pelanggaran')
                    ->required(),
                TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->placeholder('Masukkan Keterangan Kategori Pelanggaran')
                    ->required(),
            ]);
    }
}
