<?php

namespace App\Filament\Resources\JenisSurats\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JenisSuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_jenis')
                    ->label('Nama Jenis Surat')
                    ->placeholder('Masukan Nama Jenis Surat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('kode_angka')
                    ->label('Kode Angka')
                    ->placeholder('Masukan Kode Angka')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
