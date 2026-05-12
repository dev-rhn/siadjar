<?php

namespace App\Filament\Resources\DataSantris\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
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
                            ->placeholder('Masukkan Nomor Kartu Keluarga'),
                        TextInput::make('nik')
                            ->label('NIK')
                            ->placeholder('Masukkan NIK')
                            ->helperText('Pastikan NIK terdiri dari 16 digit angka.')
                            ->maxLength(16)
                            ->unique(),
                        TextInput::make('nisn')
                            ->label('NISN')
                            ->placeholder('Masukkan NISN')
                            ->helperText('Pastikan NISN terdiri dari 10 digit angka.'),
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
                            ->maxLength(3),
                        TextInput::make('rw')
                            ->label('RW')
                            ->placeholder('Masukkan RW')
                            ->maxLength(3),
                        TextInput::make('kel')
                            ->label('Kelurahan')
                            ->placeholder('Masukkan Kelurahan'),
                        TextInput::make('kec')
                            ->label('Kecamatan')
                            ->placeholder('Masukkan Kecamatan'),
                        TextInput::make('kab')
                            ->label('Kabupaten/Kota')
                            ->placeholder('Masukkan Kabupaten/Kota'),
                        TextInput::make('prov')
                            ->label('Provinsi')
                            ->placeholder('Masukkan Provinsi'),
                        TextInput::make('kode_pos')
                            ->label('Kode Pos')
                            ->placeholder('Masukkan Kode Pos')
                            ->maxLength(5),
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
                            ->live()
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

                Section::make('Data Ayah')
                    ->description('Lengkapi data ayah santri dengan benar.')
                    ->schema([
                        TextInput::make('nik_ayah')
                            ->label('NIK Ayah')
                            ->placeholder('Masukkan NIK Ayah')
                            ->helperText('Pastikan NIK Ayah terdiri dari 16 digit angka.')
                            ->maxLength(16),
                        TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->placeholder('Masukkan Nama Ayah'),
                        TextInput::make('tmp_lhr_ayah')
                            ->label('Tempat Lahir Ayah')
                            ->placeholder('Masukkan Tempat Lahir Ayah'),
                        TextInput::make('tgl_lhr_ayah')
                            ->label('Tanggal Lahir Ayah')
                            ->placeholder('Masukkan Tanggal Lahir Ayah')
                            ->type('date'),
                        TextInput::make('pendidikan_ayah')
                            ->label('Pendidikan Ayah')
                            ->placeholder('Masukkan Pendidikan Ayah'),
                        TextInput::make('pekerjaan_ayah')
                            ->label('Pekerjaan Ayah')
                            ->placeholder('Masukkan Pekerjaan Ayah'),
                ])
                ->columns(3)
                ->columnSpanFull()
                ->hidden(fn (Get $get): bool =>
                    in_array($get('keterangan'), ['Yatim', 'Yatim Piatu'])
                ),

                Section::make('Data Ibu')
                    ->description('Lengkapi data ibu santri dengan benar.')
                    ->schema([
                        TextInput::make('nik_ibu')
                            ->label('NIK Ibu')
                            ->placeholder('Masukkan NIK Ibu')
                            ->helperText('Pastikan NIK Ibu terdiri dari 16 digit angka.')
                            ->maxLength(16),
                        TextInput::make('nama_ibu')
                            ->label('Nama Ibu')
                            ->placeholder('Masukkan Nama Ibu'),
                        TextInput::make('tmp_lhr_ibu')
                            ->label('Tempat Lahir Ibu')
                            ->placeholder('Masukkan Tempat Lahir Ibu'),
                        TextInput::make('tgl_lhr_ibu')
                            ->label('Tanggal Lahir Ibu')
                            ->placeholder('Masukkan Tanggal Lahir Ibu')
                            ->type('date'),
                        TextInput::make('pendidikan_ibu')
                            ->label('Pendidikan Ibu')
                            ->placeholder('Masukkan Pendidikan Ibu'),
                        TextInput::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu')
                            ->placeholder('Masukkan Pekerjaan Ibu'),
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->hidden(fn (Get $get): bool =>
                        in_array($get('keterangan'), ['Piatu', 'Yatim Piatu'])
                    ),

                Section::make('Data Wali')
                    ->description('Lengkapi data ibu/wali santri dengan benar.')
                    ->schema([
                        TextInput::make('nik_wali')
                            ->label('NIK Wali')
                            ->placeholder('Masukkan NIK Wali')
                            ->helperText('Pastikan NIK Wali terdiri dari 16 digit angka.')
                            ->maxLength(16),
                        TextInput::make('nama_wali')
                            ->label('Nama Wali')
                            ->placeholder('Masukkan Nama Wali'),
                        TextInput::make('tmp_lhr_wali')
                            ->label('Tempat Lahir Wali')
                            ->placeholder('Masukkan Tempat Lahir Wali'),
                        TextInput::make('tgl_lhr_wali')
                            ->label('Tanggal Lahir Wali')
                            ->placeholder('Masukkan Tanggal Lahir Wali')
                            ->type('date'),
                        TextInput::make('pendidikan_wali')
                            ->label('Pendidikan Wali')
                            ->placeholder('Masukkan Pendidikan Wali'),
                        TextInput::make('pekerjaan_wali')
                            ->label('Pekerjaan Wali')
                            ->placeholder('Masukkan Pekerjaan Wali'),
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->hidden(fn (Get $get): bool =>
                        ! in_array($get('keterangan'), ['Yatim Piatu'])
                    ),
                Section::make('Dokumen Pendukung')
                    ->description('Unggah dokumen sesuai dengan status keterangan santri.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // --- KELOMPOK REGULER (Selalu Muncul) ---
                                FileUpload::make('foto_santri')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/foto')
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->label('Foto Santri')
                                    ->helperText('Gunakan latar belakang biru/merah, max 2MB.'),

                                FileUpload::make('foto_kk')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/kk')
                                    ->label('Foto Kartu Keluarga')
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->helperText('Pastikan seluruh bagian KK terlihat jelas, max 5MB.'),

                                FileUpload::make('foto_akte')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/akte')
                                    ->label('Foto Akte Kelahiran')
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->helperText('Maksimal 5MB.'),

                                FileUpload::make('ijazah')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/ijazah')
                                    ->label('Foto Ijazah Terakhir')
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->helperText('Maksimal 5MB.'),

                                FileUpload::make('nilai_rapot')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/rapot')
                                    ->label('Foto Nilai Rapot Terakhir')
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->helperText('Maksimal 5MB.'),

                                FileUpload::make('surat_ket_pindah_sekolah')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/pindah')
                                    ->label('Surat Pindah Sekolah (Opsional)')
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->helperText('Unggah jika santri pindahan.'),

                                // --- KELOMPOK DHUAFA (Hanya muncul jika status Dhuafa) ---
                                FileUpload::make('surat_ket_dhuafa')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/dhuafa')
                                    ->label('Foto Surat Keterangan Dhuafa (Opsional)')
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->visible(fn (Get $get) => $get('keterangan') === 'Dhuafa')
                                    ->helperText('Unggah bagi santri Dhuafa.'),

                                // --- KELOMPOK YATIM/PIATU (Muncul jika Yatim, Piatu, atau Yatim Piatu) ---
                                FileUpload::make('surat_kematian_org_tua')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/kematian')
                                    ->label('Foto Surat Kematian Orang Tua (Opsional)')
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->visible(fn (Get $get) => in_array($get('keterangan'), ['Yatim', 'Piatu', 'Yatim Piatu']))
                                    ->helperText('Unggah bagi santri Yatim, Piatu, atau Yatim Piatu.'),

                                // --- KELOMPOK YATIM PIATU KHUSUS ---
                                FileUpload::make('surat_ket_hak_asuh')
                                    ->image()
                                    ->disk('public')
                                    ->directory('santri/hak_asuh')
                                    ->label('Foto Surat Keterangan Hak Asuh (Opsional)')
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->visible(fn (Get $get) => $get('keterangan') === 'Yatim Piatu')
                                    ->helperText('Unggah bagi santri Yatim Piatu.'),
                            ]),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }
}
