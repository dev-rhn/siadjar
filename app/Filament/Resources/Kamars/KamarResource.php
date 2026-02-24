<?php

namespace App\Filament\Resources\Kamars;

use App\Filament\Resources\Kamars\Pages\CreateKamar;
use App\Filament\Resources\Kamars\Pages\EditKamar;
use App\Filament\Resources\Kamars\Pages\ListKamars;
use App\Filament\Resources\Kamars\Pages\ViewKamar;
use App\Filament\Resources\Kamars\Schemas\KamarForm;
use App\Filament\Resources\Kamars\Schemas\KamarInfolist;
use App\Filament\Resources\Kamars\Tables\KamarsTable;
use App\Models\Kamar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class KamarResource extends Resource
{
    protected static ?string $model = Kamar::class;

    protected static string|UnitEnum|null $navigationGroup = 'Santri Management';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'nama_kamar';

    protected static ?string $navigationLabel = 'Kamar';

    protected static ?string $slug = 'kamar';

    protected static ?string $pluralModelLabel = 'Kamar';
    
    protected static ?string $modelLabel = 'Kamar';

    public static function form(Schema $schema): Schema
    {
        return KamarForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KamarInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KamarsTable::configure($table);
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
            'index' => ListKamars::route('/'),
            'create' => CreateKamar::route('/create'),
            'view' => ViewKamar::route('/{record}'),
            'edit' => EditKamar::route('/{record}/edit'),
        ];
    }
}
