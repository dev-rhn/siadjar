<?php

namespace App\Filament\Resources\Surats\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

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
                Action::make('download_lampiran')
                    ->label('Lampiran')
                    ->button()
                    ->icon('heroicon-o-paper-clip')
                    ->color('success')
                    ->visible(fn ($record) => $record->lampiran !== null)
                    ->url(fn ($record) => Storage::disk('public')->url($record->lampiran))
                    ->openUrlInNewTab(),
                ViewAction::make()
                    ->button()
                    ->color('info'),
                EditAction::make()
                    ->button()
                    ->color('warning'),
                DeleteAction::make()
                    ->button()
                    ->color('danger'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
