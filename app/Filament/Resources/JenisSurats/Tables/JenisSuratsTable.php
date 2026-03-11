<?php

namespace App\Filament\Resources\JenisSurats\Tables;

use App\Filament\Exports\JenisSuratExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JenisSuratsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_jenis')
                    ->label('Nama Jenis Surat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kode_angka')
                    ->label('Kode Angka')
                    ->searchable()
                    ->sortable(),
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
                    ->exporter(JenisSuratExporter::class)
                    ->label('Export Data')
                    ->color('info')
            ]);
    }
}
