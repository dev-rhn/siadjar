<?php

namespace App\Filament\Resources\Kesehatans;

use App\Filament\Resources\Kesehatans\Pages\CreateKesehatan;
use App\Filament\Resources\Kesehatans\Pages\EditKesehatan;
use App\Filament\Resources\Kesehatans\Pages\ListKesehatans;
use App\Filament\Resources\Kesehatans\Pages\ViewKesehatan;
use App\Filament\Resources\Kesehatans\Schemas\KesehatanForm;
use App\Filament\Resources\Kesehatans\Schemas\KesehatanInfolist;
use App\Filament\Resources\Kesehatans\Tables\KesehatansTable;
use App\Models\Kesehatan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class KesehatanResource extends Resource
{
    protected static ?string $model = Kesehatan::class;

    protected static string|UnitEnum|null $navigationGroup = 'Kepengasuhan';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'dataSantri.nama';

    protected static ?string $navigationLabel = 'Kesehatan';

    protected static ?string $slug = 'kesehatan';

    public static function form(Schema $schema): Schema
    {
        return KesehatanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KesehatanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KesehatansTable::configure($table);
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
            'index' => ListKesehatans::route('/'),
            'create' => CreateKesehatan::route('/create'),
            'view' => ViewKesehatan::route('/{record}'),
            'edit' => EditKesehatan::route('/{record}/edit'),
        ];
    }
}
