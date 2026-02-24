<?php

namespace App\Filament\Resources\Jabatans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JabatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jabatan')
                    ->schema([
                        TextInput::make('kd_jabatan')
                            ->label('Kode Jabatan')
                            ->placeholder('Masukkan Kode Jabatan')
                            ->required(),
                        TextInput::make('nama_jabatan')
                            ->label('Nama Jabatan')
                            ->placeholder('Masukkan Nama Jabatan')
                            ->required(),
                        TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('Masukkan Keterangan Jabatan'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
