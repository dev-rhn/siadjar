<?php

namespace App\Filament\Resources\DataSantris\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
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
                            ->helperText('Pastikan nomor KK terdiri dari 16 digit angka.')
                            ->maxLength(16)
                            ->placeholder('Masukkan Nomor Kartu Keluarga')
                            ->required(),
                        TextInput::make('nik')
                            ->label('NIK')
                            ->placeholder('Masukkan NIK')
                            ->helperText('Pastikan NIK terdiri dari 16 digit angka.')
                            ->maxLength(16)
                            ->required()
                            ->unique(),
                        TextInput::make('nisn')
                            ->label('NISN')
                            ->placeholder('Masukkan NISN')
                            ->helperText('Pastikan NISN terdiri dari 10 digit angka.')
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
                            ->type('date')
                            ->placeholder('Masukkan Tanggal Lahir')
                            ->required(),
                        Select::make('jk')
                            ->label('Jenis Kelamin')
                            ->placeholder('Pilih Jenis Kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki', 
                                'Perempuan' => 'Perempuan',
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
                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->placeholder('Pilih Kelas')
                            ->relationship('kelas', 'nama_kelas')
                            ->required(),
                        Select::make('kamar_id')
                            ->label('Kamar')
                            ->placeholder('Pilih Kamar')
                            ->relationship('kamar', 'nama_kamar')
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Data Ayah / Wali')
                    ->description('Lengkapi data ayah/wali santri dengan benar.')
                    ->schema([
                        TextInput::make('nik_ayah')
                            ->label('NIK Ayah/Wali')
                            ->placeholder('Masukkan NIK Ayah/Wali')
                            ->helperText('Pastikan NIK Ayah/Wali terdiri dari 16 digit angka.')
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
                            ->helperText('Pastikan NIK Ibu/Wali terdiri dari 16 digit angka.')
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
                Section::make('Dokumen Pendukung')
                    ->description('Unggah foto santri dan scan Kartu Keluarga dalam format JPG/PNG.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('foto_santri')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/foto') 
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->visibility('public')
                                    ->label('Foto Santri')
                                    ->helperText('Gunakan latar belakang biru/merah, max 2MB.'),

                                FileUpload::make('foto_kk')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/kk')
                                    ->label('Foto Kartu Keluarga')
                                    ->visibility('public')
                                    ->imageEditor()
                                    ->helperText('Pastikan seluruh bagian KK terlihat jelas.'),
                            ]),
                    ])->collapsible()
                    ->columnSpanFull(),
            ]);
    }
}
