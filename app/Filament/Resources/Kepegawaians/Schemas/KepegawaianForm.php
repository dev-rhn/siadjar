<?php

namespace App\Filament\Resources\Kepegawaians\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KepegawaianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Section::make('Akun Akses Sistem')
                        ->description('Pilih user yang akan dihubungkan dengan data kepegawaian ini.')
                        ->schema([
                            Select::make('user_id')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->preload()
                                ->unique(ignoreRecord: true)
                                ->helperText('Satu user hanya bisa memiliki satu profil pegawai.'),
                        ])->columnSpanFull(),

                    Section::make('Profil Kepegawaian')
                        ->schema([
                            TextInput::make('kd_pegawai')
                                ->required()
                                ->label('Kode Pegawai')
                                ->placeholder('Masukkan Kode Pegawai')
                                ->unique(ignoreRecord: true),
                            TextInput::make('nama_pegawai')
                                ->label('Nama Pegawai')
                                ->placeholder('Masukkan Nama Pegawai')
                                ->required(),
                            TextInput::make('no_telepon')
                                ->label('No. Telepon')
                                ->placeholder('Masukkan No. Telepon Pegawai')
                                ->required(),
                            Select::make('jabatan_id')
                                ->label('Jabatan')
                                ->relationship('jabatan', 'nama_jabatan') 
                                ->searchable()
                                ->preload()
                                ->required()
                                ->placeholder('Pilih Jabatan Pegawai'),
                            Select::make('status_kepegawaian')
                                ->options([
                                    'Tetap' => 'Tetap',
                                    'Kontrak' => 'Kontrak',
                                    'Honor' => 'Honor',
                                ])
                                ->required(),
                            TextInput::make('alamat')
                                ->label('Alamat')
                                ->placeholder('Masukkan Alamat Pegawai')
                                ->required(),
                        ])
                        ->columnSpanFull()
                        ->columns(2),
            ]);
    }
}
