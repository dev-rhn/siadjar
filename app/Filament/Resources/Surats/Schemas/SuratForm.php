<?php

namespace App\Filament\Resources\Surats\Schemas;

use App\Models\Surat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class SuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('arah')
                    ->label('Arah Surat')
                    ->options(['Masuk' => 'Surat Masuk', 'Keluar' => 'Surat Keluar'])
                    ->required()
                    ->live() // sudah ada
                    ->afterStateUpdated(function ($set, $get) {
                        if ($get('arah') && $get('jenis_surat_id') && $get('tanggal')) {
                            $set('nomor_surat', Surat::generateNomor(
                                $get('arah'),
                                $get('jenis_surat_id'),
                                $get('tanggal')
                            ));
                        }
                    }),

                Select::make('jenis_surat_id')
                    ->label('Jenis Surat')
                    ->relationship('jenisSurat', 'nama_jenis')
                    ->required()
                    ->live() // sudah ada
                    ->afterStateUpdated(function ($set, $get) {
                        if ($get('arah') && $get('jenis_surat_id') && $get('tanggal')) {
                            $set('nomor_surat', Surat::generateNomor(
                                $get('arah'),
                                $get('jenis_surat_id'),
                                $get('tanggal')
                            ));
                        }
                    }),

                DatePicker::make('tanggal')
                    ->default(now())
                    ->required()
                    ->live() // sudah ada
                    ->afterStateUpdated(function ($set, $get) {
                        if ($get('arah') && $get('jenis_surat_id') && $get('tanggal')) {
                            $set('nomor_surat', Surat::generateNomor(
                                $get('arah'),
                                $get('jenis_surat_id'),
                                $get('tanggal')
                            ));
                        }
                    }),

                TextInput::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->disabled()
                    ->dehydrated()
                    ->placeholder('Otomatis terisi'),

            TextInput::make('perihal')->required()->columnSpanFull(),

            Select::make('pegawai_id')
                ->label('Pembuat Surat')
                ->relationship('pegawai', 'nama_pegawai')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('status')
                ->options([
                    'Draft' => 'Draft',
                    'Diajukan' => 'Diajukan',
                    'Disetujui' => 'Disetujui',
                    'Ditolak' => 'Ditolak',
                ])
                ->default('Draft')
                ->required(),

            Textarea::make('keterangan')->columnSpanFull(),
            
            FileUpload::make('lampiran')
                ->label('Lampiran')
                ->disk('public')
                ->directory('lampiran-surat')
                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->maxSize(5120) // 5MB
                ->downloadable()
                ->openable()
                ->columnSpanFull(),

            Repeater::make('penerimaSurats')
                ->label('Penerima Surat')
                ->relationship()
                ->schema([
                    TextInput::make('nama_penerima')
                        ->label('Nama Penerima')
                        ->required(),
                ])
                ->columnSpanFull()
                ->addActionLabel('Tambah Penerima'),
            ]);
    }
}
