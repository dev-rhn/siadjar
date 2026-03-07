<?php

namespace App\Filament\Resources\Kamars\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KamarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kamar')
                    ->schema([
                        TextInput::make('kd_kamar')
                            ->label('Kode Kamar')
                            ->placeholder('Masukkan Kode Kamar')
                            ->required(),
                        TextInput::make('nama_kamar')
                            ->label('Nama Kamar')
                            ->placeholder('Masukkan Nama Kamar')
                            ->required(),
                        TextInput::make('kapasitas')
                            ->label('Kapasitas')
                            ->placeholder('Masukkan Kapasitas Kamar')
                            ->required(),
                        Select::make('pegawai_id')
                            ->label('Nama Pengasuh')
                            ->relationship('pengasuh', 'nama_pegawai')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
