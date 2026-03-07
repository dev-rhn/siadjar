<?php

namespace App\Filament\Resources\Pelanggarans\Tables;

use App\Filament\Exports\PelanggaranExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PelanggaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kategoriPelanggaran.nama_kategori')
                    ->label('Kategori Pelanggaran')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_pelanggaran')
                    ->label('Nama Pelanggaran')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('poin')
                    ->label('Poin Pelanggaran')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
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
                ExportAction::make()
                    ->exporter(PelanggaranExporter::class)
                    ->label('Export Data')
                    ->color('info')
            ]);
    }
}
