<?php

namespace App\Filament\Resources\Kelas;

use App\Filament\Resources\Kelas\Pages\CreateKelas;
use App\Filament\Resources\Kelas\Pages\EditKelas;
use App\Filament\Resources\Kelas\Pages\ListKelas;
use App\Filament\Resources\Kelas\Pages\ViewKelas;
use App\Filament\Resources\Kelas\Schemas\KelasForm;
use App\Filament\Resources\Kelas\Schemas\KelasInfolist;
use App\Filament\Resources\Kelas\Tables\KelasTable;
use App\Models\Kelas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static string|UnitEnum|null $navigationGroup = 'Santri Management';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'nama_kelas';

    protected static ?string $navigationLabel = 'Kelas';

    protected static ?string $slug = 'kelas';

    protected static ?string $pluralModelLabel = 'Kelas';
    
    protected static ?string $modelLabel = 'Kelas';

    public static function form(Schema $schema): Schema
    {
        return KelasForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KelasInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KelasTable::configure($table);
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
            'index' => ListKelas::route('/'),
            'create' => CreateKelas::route('/create'),
            'view' => ViewKelas::route('/{record}'),
            'edit' => EditKelas::route('/{record}/edit'),
        ];
    }
}
