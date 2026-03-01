<?php

namespace App\Filament\Resources\CatatanPelanggarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CatatanPelanggaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Select::make('data_santri_id')
                ->label('Santri')
                ->relationship('dataSantri', 'nama')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('pelanggaran_id')
                ->label('Jenis Pelanggaran')
                ->relationship('pelanggaran', 'nama_pelanggaran')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_pelanggaran} ({$record->poin} Poin)")
                ->searchable()
                ->preload()
                ->required(),

            DatePicker::make('tanggal_kejadian')
                ->default(now())
                ->required(),

            Textarea::make('catatan')
                ->columnSpanFull(),
            ]);
    }
}
