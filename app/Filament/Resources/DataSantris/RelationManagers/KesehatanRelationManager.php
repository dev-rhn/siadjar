<?php

namespace App\Filament\Resources\DataSantris\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KesehatanRelationManager extends RelationManager
{
    protected static string $relationship = 'kesehatan';

    protected static ?string $title = 'Data Kesehatan';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_penyakit')
                    ->label('Nama Penyakit')
                    ->placeholder('Masukkan Nama Penyakit')
                    ->required(),
                DatePicker::make('tanggal_sakit')
                    ->label('Tanggal Sakit')
                    ->default(now())
                    ->required(),
                TextInput::make('obat')
                    ->label('Obat')
                    ->placeholder('Masukkan Nama Obat yang Diberikan'),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->placeholder('Masukkan Keterangan Tambahan')
                    ->columnSpanFull(),
                Toggle::make('is_sembuh')
                    ->label('Sembuh')
                    ->default(false),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_penyakit')
                    ->label('Nama Penyakit')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('tanggal_sakit')
                    ->label('Tanggal Sakit')
                    ->date()
                    ->sortable(),
                TextColumn::make('obat')->label('Obat'),
                IconColumn::make('is_sembuh')
                    ->label('Status')
                    ->boolean() 
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('keterangan')->label('Keterangan')->placeholder('-'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Data Kesehatan')
                    ->relationship(fn () => $this->getRelationship()),
            ])
            ->defaultSort('tanggal_sakit', 'desc');
    }
    
}
