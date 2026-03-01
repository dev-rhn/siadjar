<?php

namespace App\Filament\Resources\DataSantris\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CatatanPelanggaransRelationManager extends RelationManager
{
    protected static string $relationship = 'catatanPelanggarans';

    protected static ?string $modelLabel = 'Catatan Pelanggaran';
    
    protected static ?string $title = 'Catatan Pelanggaran';

    // protected static ?string $relatedResource = DataSantriResource::class;

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_kejadian')->date()->sortable(),
                TextColumn::make('pelanggaran.nama_pelanggaran')->label('Pelanggaran'),
                TextColumn::make('pelanggaran.poin')
                    ->label('Poin')
                    ->badge()
                    ->color('danger')
                    ->inverseRelationship('catatanPelanggarans')
                    ->summarize(Sum::make()->label('Total Poin')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Catat Pelanggaran Baru')
                    ->modalHeading('Tambah Catatan Pelanggaran Santri')
                    ->relationship(fn () => $this->getRelationship()),
            ]);
    }
}