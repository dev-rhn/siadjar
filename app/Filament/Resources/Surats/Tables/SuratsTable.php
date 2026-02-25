<?php

namespace App\Filament\Resources\Surats\Tables;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SuratsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('arah')
                    ->label('Arah')
                    ->badge()
                    ->color(fn ($state) => $state === 'Masuk' ? 'info' : 'success'),
                TextColumn::make('jenisSurat.nama_jenis')
                    ->label('Jenis Surat'),
                TextColumn::make('perihal')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('pegawai.nama_pegawai')
                    ->label('Pembuat'),
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('penerimaSurats.nama_penerima')
                    ->label('Penerima')
                    ->badge()
                    ->color('info')
                    ->separator(','),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'Draft' => 'gray',
                        'Diajukan' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('arah')->options(['Masuk' => 'Surat Masuk', 'Keluar' => 'Surat Keluar']),
                SelectFilter::make('status')->options([
                    'Draft' => 'Draft',
                    'Diajukan' => 'Diajukan',
                    'Disetujui' => 'Disetujui',
                    'Ditolak' => 'Ditolak',
                ]),
                SelectFilter::make('jenis_surat_id')
                    ->label('Jenis Surat')
                    ->relationship('jenisSurat', 'nama_jenis'),
            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
