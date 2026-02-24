<?php

namespace App\Filament\Resources\Kepegawaians\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KepegawaiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kd_pegawai')
                    ->label('Kode Pegawai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jabatan.nama_jabatan')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('no_telp')
                    ->label('No. Telepon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_kepegawaian')
                    ->label('Status Pegawai')
                    ->sortable(),
                TextColumn::make('tanggal_mulai_tugas')
                    ->label('Tanggal Mulai Tugas')
                    ->date()
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
            ]);
    }
}
