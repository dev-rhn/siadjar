<?php

namespace App\Filament\Resources\CatatanPelanggarans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CatatanPelanggaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dataSantri.nama')
                    ->label('Nama Santri')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pelanggaran.kategoriPelanggaran.nama_kategori')
                    ->label('Kategori')
                    ->sortable(),

                TextColumn::make('pelanggaran.nama_pelanggaran')
                    ->label('Pelanggaran')
                    ->searchable(),

                TextColumn::make('pelanggaran.poin')
                    ->label('Poin')
                    ->badge()
                    ->color('danger'),

                TextColumn::make('tanggal_kejadian')
                    ->label('Tanggal Kejadian')
                    ->date()
                    ->sortable(),

                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(50)
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('pelanggaran_id')
                    ->label('Jenis Pelanggaran')
                    ->relationship('pelanggaran', 'nama_pelanggaran'),

                SelectFilter::make('data_santri_id')
                    ->label('Santri')
                    ->relationship('dataSantri', 'nama'),
            ])
            ->defaultSort('tanggal_kejadian', 'desc')
            ->recordActions([
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
