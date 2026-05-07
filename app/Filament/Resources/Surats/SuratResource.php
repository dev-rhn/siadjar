<?php

namespace App\Filament\Resources\Surats;

use App\Filament\Resources\Surats\Pages\CreateSurat;
use App\Filament\Resources\Surats\Pages\EditSurat;
use App\Filament\Resources\Surats\Pages\ListSurats;
use App\Filament\Resources\Surats\Pages\ViewSurat;
use App\Filament\Resources\Surats\Schemas\SuratForm;
use App\Filament\Resources\Surats\Schemas\SuratInfolist;
use App\Filament\Resources\Surats\Tables\SuratsTable;
use App\Models\Surat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class SuratResource extends Resource
{
    protected static ?string $model = Surat::class;

    protected static string|UnitEnum|null $navigationGroup = 'Administrasi';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'nomor_surat';
    
    protected static ?string $navigationLabel = 'Data Surat';

    protected static ?string $slug = 'surat';
    
    protected static ?string $pluralModelLabel = 'Data Surat';
    
    protected static ?string $modelLabel = 'Data Surat';

    public static function form(Schema $schema): Schema
    {
        return SuratForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SuratInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuratsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSurats::route('/'),
            'create' => CreateSurat::route('/create'),
            'view' => ViewSurat::route('/{record}'),
            'edit' => EditSurat::route('/{record}/edit'),
        ];
    }
}
