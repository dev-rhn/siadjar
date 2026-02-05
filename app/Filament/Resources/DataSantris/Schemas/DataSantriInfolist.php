<?php

namespace App\Filament\Resources\DataSantris\Schemas;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;

class DataSantriInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Data Santri")
                    ->columns(3)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('no_kk')
                            ->label('Nomor Kartu Keluarga'),
                        TextEntry::make('nik')
                            ->label('NIK'),
                        TextEntry::make('nisn')
                            ->label('NISN'),
                        TextEntry::make('nama')
                            ->label('Nama Lengkap'),
                        TextEntry::make('jk')
                            ->label('Jenis Kelamin'),
                        TextEntry::make('tmp_lhr')
                            ->label('Tempat Lahir'),
                        TextEntry::make('tgl_lhr')
                            ->label('Tanggal Lahir')
                            ->date(),
                    ]),
                Section::make("Data Alamat")
                    ->columns(3)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('alamat')
                            ->label('Alamat')
                            ->columnSpanFull(),
                        TextEntry::make('rt')
                            ->label('RT'),
                        TextEntry::make('rw')
                            ->label('RW'),
                        TextEntry::make('kel')
                            ->label('Kelurahan/Desa'),
                        TextEntry::make('kec')
                            ->label('Kecamatan'),
                        TextEntry::make('kab')
                            ->label('Kabupaten/Kota'),
                        TextEntry::make('prov')
                            ->label('Provinsi'),
                    ]),
                Section::make("Data Pendidikan")
                    ->columns(4)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('asal_sekolah')
                            ->label('Asal Sekolah')
                            ->columnSpanFull(),
                        TextEntry::make('tahun_masuk')
                            ->label('Tahun Masuk'),
                        TextEntry::make('jenjang')
                            ->label('Jenjang')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'SD' => 'danger',
                                'SMP' => 'info',
                                'SMA' => 'success',
                                default => 'gray',
                            }),
                        TextEntry::make('keterangan')
                            ->label('Keterangan')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Dhuafa' => 'success',
                                'Yatim' => 'warning',
                                'Piatu' => 'info',
                                'Yatim Piatu' => 'danger',
                                'Reguler' => 'gray',
                                default => 'gray',
                            }),
                        TextEntry::make('status')
                            ->label('Status Santri')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Aktif' => 'success',
                                'Tidak Aktif' => 'danger',
                                'Cuti' => 'warning',
                                default => 'gray',
                            }),
                    ]),
                Section::make('Data Orang Tua/Wali')
                    ->columns(3)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('nik_ayah')
                            ->label('NIK Ayah'),
                        TextEntry::make('nama_ayah')
                            ->label('Nama Ayah'),
                        TextEntry::make('tmp_lhr_ayah')
                            ->label('Tempat Lahir Ayah'),
                        TextEntry::make('tgl_lhr_ayah')
                            ->label('Tanggal Lahir Ayah'),
                        TextEntry::make('pekerjaan_ayah')
                            ->label('Pekerjaan Ayah'),
                        TextEntry::make('pendidikan_ayah')
                            ->label('Pendidikan Ayah'),

                        TextEntry::make('nik_ibu')
                            ->label('NIK Ibu'),
                        TextEntry::make('nama_ibu')
                            ->label('Nama Ibu'),
                        TextEntry::make('tmp_lhr_ibu')
                            ->label('Tempat Lahir Ibu'),
                        TextEntry::make('tgl_lhr_ibu')
                            ->label('Tanggal Lahir Ibu'),
                        TextEntry::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu'),
                        TextEntry::make('pendidikan_ibu')
                            ->label('Pendidikan Ibu'),
                    ]),
                Section::make('Informasi Tambahan')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Diperbarui Pada')
                            ->dateTime('d M Y H:i'),
                    ]),
            ]);
    }
}
