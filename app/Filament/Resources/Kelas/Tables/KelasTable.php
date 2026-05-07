<?php

namespace App\Filament\Resources\Kelas\Tables;

use App\Filament\Exports\KelasExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kd_kelas')
                    ->label('Kode Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('waliKelas.nama_pegawai')
                    ->label('Wali Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
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
                    ->exporter(KelasExporter::class)
                    ->label('Export Data')
                    ->color('info')
            ]);
    }
}
