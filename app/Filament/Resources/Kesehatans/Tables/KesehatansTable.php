<?php

namespace App\Filament\Resources\Kesehatans\Tables;

use App\Filament\Exports\KesehatanExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Builder;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KesehatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dataSantri.nama')
                    ->label('Nama Santri')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('nama_penyakit')
                    ->label('Diagnosa')
                    ->badge()
                    ->color('danger'),
                    
                TextColumn::make('tanggal_sakit')
                    ->date()
                    ->sortable(),
                    
                TextColumn::make('obat')
                    ->placeholder('Belum ada obat'),

                TextColumn::make('keterangan')
                    ->limit(50)
                    ->wrap()
                    ->placeholder('Tidak ada keterangan'), 
                IconColumn::make('is_sembuh')
                    ->label('Status')
                    ->boolean() 
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Filter::make('sakit_hari_ini')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal_sakit', now())),

                SelectFilter::make('jenjang')
                    ->relationship('dataSantri', 'jenjang')
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
                    ->exporter(KesehatanExporter::class)
                    ->label('Export Data')
                    ->color('info')
            ]);
    }
}
