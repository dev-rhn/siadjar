<?php

namespace App\Filament\Resources\DataSantris\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

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

                Section::make('Data Ayah')
                    ->columns(3)
                    ->columnSpanFull()
                    ->hidden(fn ($record): bool =>
                        in_array($record?->keterangan, ['Yatim', 'Yatim Piatu'])
                    )
                    ->components([
                        TextEntry::make('nik_ayah')
                            ->label('NIK Ayah'),
                        TextEntry::make('nama_ayah')
                            ->label('Nama Ayah'),
                        TextEntry::make('tmp_lhr_ayah')
                            ->label('Tempat Lahir Ayah'),
                        TextEntry::make('tgl_lhr_ayah')
                            ->label('Tanggal Lahir Ayah')
                            ->date(),
                        TextEntry::make('pekerjaan_ayah')
                            ->label('Pekerjaan Ayah'),
                        TextEntry::make('pendidikan_ayah')
                            ->label('Pendidikan Ayah'),
                    ]),

                Section::make('Data Ibu')
                    ->columns(3)
                    ->columnSpanFull()
                    ->hidden(fn ($record): bool =>
                        in_array($record?->keterangan, ['Piatu', 'Yatim Piatu'])
                    )
                    ->components([
                        TextEntry::make('nik_ibu')
                            ->label('NIK Ibu'),
                        TextEntry::make('nama_ibu')
                            ->label('Nama Ibu'),
                        TextEntry::make('tmp_lhr_ibu')
                            ->label('Tempat Lahir Ibu'),
                        TextEntry::make('tgl_lhr_ibu')
                            ->label('Tanggal Lahir Ibu')
                            ->date(),
                        TextEntry::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu'),
                        TextEntry::make('pendidikan_ibu')
                            ->label('Pendidikan Ibu'),
                    ]),

                Section::make('Data Wali')
                    ->columns(3)
                    ->columnSpanFull()
                    ->hidden(fn ($record): bool =>
                        ! in_array($record?->keterangan, ['Yatim Piatu'])
                    )
                    ->components([
                        TextEntry::make('nik_wali')
                            ->label('NIK Wali'),
                        TextEntry::make('nama_wali')
                            ->label('Nama Wali'),
                        TextEntry::make('tmp_lhr_wali')
                            ->label('Tempat Lahir Wali'),
                        TextEntry::make('tgl_lhr_wali')
                            ->label('Tanggal Lahir Wali')
                            ->date(),
                        TextEntry::make('pekerjaan_wali')
                            ->label('Pekerjaan Wali'),
                        TextEntry::make('pendidikan_wali')
                            ->label('Pendidikan Wali'),
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

            Section::make("Dokumen Pendukung")
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    // --- KELOMPOK REGULER (Selalu Muncul) ---
                    ImageEntry::make('foto_santri')
                        ->label('Foto Santri')
                        ->disk('public')
                        ->visibility('public'),

                    ImageEntry::make('foto_kk')
                        ->label('Foto Kartu Keluarga')
                        ->disk('public')
                        ->visibility('public'),

                    ImageEntry::make('foto_akte')
                        ->label('Foto Akte Kelahiran')
                        ->disk('public')
                        ->visibility('public'),

                    ImageEntry::make('ijazah')
                        ->label('Foto Ijazah Terakhir')
                        ->disk('public')
                        ->visibility('public'),

                    ImageEntry::make('nilai_rapot')
                        ->label('Foto Nilai Rapot Terakhir')
                        ->disk('public')
                        ->visibility('public'),

                    ImageEntry::make('surat_ket_pindah_sekolah')
                        ->label('Surat Pindah Sekolah')
                        ->disk('public')
                        ->visibility('public')
                        ->visible(fn ($record) => $record->surat_ket_pindah_sekolah !== null),

                    // --- KELOMPOK DHUAFA ---
                    ImageEntry::make('surat_ket_dhuafa')
                        ->label('Surat Keterangan Dhuafa')
                        ->disk('public')
                        ->visibility('public')
                        ->visible(fn ($record) => $record->keterangan === 'Dhuafa'),

                    // --- KELOMPOK YATIM/PIATU ---
                    ImageEntry::make('surat_kematian_org_tua')
                        ->label('Surat Kematian Orang Tua')
                        ->disk('public')
                        ->visibility('public')
                        ->visible(fn ($record) => in_array($record->keterangan, ['Yatim', 'Piatu', 'Yatim Piatu'])),

                    // --- KELOMPOK YATIM PIATU KHUSUS ---
                    ImageEntry::make('surat_ket_hak_asuh')
                        ->label('Surat Keterangan Hak Asuh')
                        ->disk('public')
                        ->visibility('public')
                        ->visible(fn ($record) => $record->keterangan === 'Yatim Piatu'),
                ]),
            ]);
    }
}
