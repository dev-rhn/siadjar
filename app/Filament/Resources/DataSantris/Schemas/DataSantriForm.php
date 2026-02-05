<?php

namespace App\Filament\Resources\DataSantris\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DataSantriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Identitas Utama')
                    ->description('Lengkapi data identitas utama santri dengan benar.')
                    ->schema([
                        TextInput::make('no_kk')
                            ->label('Nomor Kartu Keluarga')
                            ->maxLength(16)
                            ->placeholder('Masukkan Nomor Kartu Keluarga')
                            ->required(),
                        TextInput::make('nik')
                            ->label('NIK')
                            ->placeholder('Masukkan NIK')
                            ->maxLength(16)
                            ->required()
                            ->unique(),
                        TextInput::make('nisn')
                            ->label('NISN')
                            ->placeholder('Masukkan NISN')
                            ->required(),
                        TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->placeholder('Masukkan Nama Lengkap')
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('tmp_lhr')
                            ->label('Tempat Lahir')
                            ->placeholder('Masukkan Tempat Lahir')
                            ->required(),
                        TextInput::make('tgl_lhr')
                            ->label('Tanggal Lahir')
                            ->placeholder('Masukkan Tanggal Lahir')
                            ->required(),
                        Select::make('jk')
                            ->label('Jenis Kelamin')
                            ->placeholder('Pilih Jenis Kelamin')
                            ->options([
                                'Laki-laki', 
                                'Perempuan'
                                ])
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Data Alamat')
                    ->description('Lengkapi data alamat santri dengan benar.')
                    ->schema([
                        TextInput::make('alamat')
                            ->label('Alamat Lengkap')
                            ->placeholder('Masukkan Alamat Lengkap')
                            ->required(),
                        TextInput::make('rt')
                            ->label('RT')
                            ->placeholder('Masukkan RT')
                            ->maxLength(3)
                            ->required(),
                        TextInput::make('rw')
                            ->label('RW')
                            ->placeholder('Masukkan RW')
                            ->maxLength(3)
                            ->required(),
                        TextInput::make('kel')
                            ->label('Kelurahan')
                            ->placeholder('Masukkan Kelurahan')
                            ->required(),
                        TextInput::make('kec')
                            ->label('Kecamatan')
                            ->placeholder('Masukkan Kecamatan')
                            ->required(),
                        TextInput::make('kab')
                            ->label('Kabupaten/Kota')
                            ->placeholder('Masukkan Kabupaten/Kota')
                            ->required(),
                        TextInput::make('prov')
                            ->label('Provinsi')
                            ->placeholder('Masukkan Provinsi')
                            ->required(),
                        TextInput::make('kode_pos')
                            ->label('Kode Pos')
                            ->placeholder('Masukkan Kode Pos')
                            ->maxLength(5)
                            ->required(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
                Section::make('Data Pendidikan & Status')
                    ->description('Lengkapi data pendidikan dan status santri dengan benar.')
                    ->schema([
                        TextInput::make('asal_sekolah')
                            ->label('Asal Sekolah')
                            ->placeholder('Masukkan Asal Sekolah')
                            ->columnSpanFull()
                            ->required(),
                        Select::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('Pilih Keterangan')
                            ->options([
                                'Dhuafa' => 'Dhuafa',
                                'Yatim' => 'Yatim',
                                'Piatu' => 'Piatu',
                                'Yatim Piatu' => 'Yatim Piatu',
                                'Reguler' => 'Reguler',
                            ])
                            ->default('Reguler')
                            ->required(),
                        Select::make('status')
                            ->label('Status Santri')
                            ->placeholder('Pilih Status')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Tidak Aktif' => 'Tidak Aktif',
                            ])
                            ->default('Aktif')
                            ->required(),
                        TextInput::make('tahun_masuk')
                            ->label('Tahun Masuk')
                            ->placeholder('Masukkan Tahun Masuk')
                            ->type('number')
                            ->required(),
                        Select::make('jenjang')
                            ->label('Jenjang')
                            ->placeholder('Pilih Jenjang')
                            ->options([
                                'SD' => 'SD',
                                'SMP' => 'SMP',
                                'SMA' => 'SMA',
                            ])
                            ->required(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
                Section::make('Data Ayah / Wali')
                    ->description('Lengkapi data ayah/wali santri dengan benar.')
                    ->schema([
                        TextInput::make('nik_ayah')
                            ->label('NIK Ayah/Wali')
                            ->placeholder('Masukkan NIK Ayah/Wali')
                            ->required()
                            ->maxLength(16),
                        TextInput::make('nama_ayah')
                            ->label('Nama Ayah/Wali')
                            ->placeholder('Masukkan Nama Ayah/Wali')
                            ->required(),
                        TextInput::make('tmp_lhr_ayah')
                            ->label('Tempat Lahir Ayah/Wali')
                            ->placeholder('Masukkan Tempat Lahir Ayah/Wali')
                            ->required(),
                        TextInput::make('tgl_lhr_ayah')
                            ->label('Tanggal Lahir Ayah/Wali')
                            ->placeholder('Masukkan Tanggal Lahir Ayah/Wali')
                            ->required()
                            ->type('date'),
                        TextInput::make('pendidikan_ayah')
                            ->label('Pendidikan Ayah/Wali')
                            ->placeholder('Masukkan Pendidikan Ayah/Wali')
                            ->required(),
                        TextInput::make('pekerjaan_ayah')
                            ->label('Pekerjaan Ayah/Wali')
                            ->placeholder('Masukkan Pekerjaan Ayah/Wali')
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Data Ibu / Wali')
                    ->description('Lengkapi data ibu/wali santri dengan benar.')
                    ->schema([
                        TextInput::make('nik_ibu')
                            ->label('NIK Ibu/Wali')
                            ->placeholder('Masukkan NIK Ibu/Wali')
                            ->required()
                            ->maxLength(16),
                        TextInput::make('nama_ibu')
                            ->label('Nama Ibu/Wali')
                            ->placeholder('Masukkan Nama Ibu/Wali')
                            ->required(),
                        TextInput::make('tmp_lhr_ibu')
                            ->label('Tempat Lahir Ibu/Wali')
                            ->placeholder('Masukkan Tempat Lahir Ibu/Wali')
                            ->required(),
                        TextInput::make('tgl_lhr_ibu')
                            ->label('Tanggal Lahir Ibu/Wali')
                            ->placeholder('Masukkan Tanggal Lahir Ibu/Wali')
                            ->type('date')
                            ->required(),
                        TextInput::make('pendidikan_ibu')
                            ->label('Pendidikan Ibu/Wali')
                            ->placeholder('Masukkan Pendidikan Ibu/Wali')
                            ->required(),
                        TextInput::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu/Wali')
                            ->placeholder('Masukkan Pekerjaan Ibu/Wali')
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
