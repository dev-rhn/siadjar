<?php

namespace App\Filament\Resources\Kelas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kelas')
                    ->schema([
                        TextInput::make('kd_kelas')
                            ->label('Kode Kelas')
                            ->placeholder('Masukkan Kode Kelas')
                            ->required(),
                        TextInput::make('nama_kelas')
                            ->label('Nama Kelas')
                            ->placeholder('Masukkan Nama Kelas')
                            ->required(),
                        Select::make('tingkat')
                            ->label('Tingkat')
                            ->placeholder('Masukkan Tingkat Kelas')
                            ->options([
                                'SD' => 'SD',
                                'SMP' => 'SMP',
                                'SMA' => 'SMA',
                            ])
                            ->required(),
                        TextInput::make('wali_kelas')
                            ->label('Wali Kelas')
                            ->placeholder('Masukkan Nama Wali Kelas')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
