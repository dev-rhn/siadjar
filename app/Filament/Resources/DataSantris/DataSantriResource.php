<?php

namespace App\Filament\Resources\DataSantris;

use App\Filament\Resources\DataSantris\Pages\CreateDataSantri;
use App\Filament\Resources\DataSantris\Pages\EditDataSantri;
use App\Filament\Resources\DataSantris\Pages\ListDataSantris;
use App\Filament\Resources\DataSantris\Pages\ViewDataSantri;
use App\Filament\Resources\DataSantris\Schemas\DataSantriForm;
use App\Filament\Resources\DataSantris\Schemas\DataSantriInfolist;
use App\Filament\Resources\DataSantris\Tables\DataSantrisTable;
use App\Models\DataSantri;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DataSantriResource extends Resource
{
    protected static ?string $model = DataSantri::class;

    protected static string|UnitEnum|null $navigationGroup = 'Santri Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Data Santri';

    protected static ?string $navigationLabel = 'Data Santri';

    protected static ?string $slug = 'data-santri';

    protected static ?string $pluralModelLabel = 'Data Santri';
    
    protected static ?string $modelLabel = 'Data Santri';

    public static function form(Schema $schema): Schema
    {
        return DataSantriForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DataSantriInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataSantrisTable::configure($table);
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
            'index' => ListDataSantris::route('/'),
            'create' => CreateDataSantri::route('/create'),
            'view' => ViewDataSantri::route('/{record}'),
            'edit' => EditDataSantri::route('/{record}/edit'),
        ];
    }
}
