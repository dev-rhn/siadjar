<?php

namespace App\Filament\Resources\Kesehatans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KesehatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kesehatan')
                    ->schema([
                        Select::make('data_santri_id')
                            ->label('Nama Santri')
                            ->relationship('dataSantri', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),
                        DatePicker::make('tanggal_sakit')
                            ->label('Tanggal Sakit')
                            ->required(),
                        TextInput::make('nama_penyakit')
                            ->label('Nama Penyakit')
                            ->placeholder('Masukkan Nama Penyakit')
                            ->required(),
                        TextInput::make('obat')
                            ->label('Obat')
                            ->placeholder('Masukkan Obat yang Diberikan'),
                        TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('Masukkan Keterangan Tambahan'),
                        Toggle::make('is_sembuh')
                            ->label('Sudah Sembuh?')
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->onColor('success')
                            ->offColor('danger')
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
