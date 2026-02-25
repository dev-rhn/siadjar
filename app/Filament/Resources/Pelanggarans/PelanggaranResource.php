<?php

namespace App\Filament\Resources\Pelanggarans;

use App\Filament\Resources\Pelanggarans\Pages\CreatePelanggaran;
use App\Filament\Resources\Pelanggarans\Pages\EditPelanggaran;
use App\Filament\Resources\Pelanggarans\Pages\ListPelanggarans;
use App\Filament\Resources\Pelanggarans\Pages\ViewPelanggaran;
use App\Filament\Resources\Pelanggarans\Schemas\PelanggaranForm;
use App\Filament\Resources\Pelanggarans\Schemas\PelanggaranInfolist;
use App\Filament\Resources\Pelanggarans\Tables\PelanggaransTable;
use App\Models\Pelanggaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PelanggaranResource extends Resource
{
    protected static ?string $model = Pelanggaran::class;

    protected static string|UnitEnum|null $navigationGroup = 'Kepengasuhan';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'nama_pelanggaran';

    protected static ?string $navigationLabel = 'Pelanggaran';

    protected static ?string $slug = 'pelanggaran';

    protected static ?string $pluralModelLabel = 'Data Pelanggaran';
    
    protected static ?string $modelLabel = 'Data Pelanggaran';

    public static function form(Schema $schema): Schema
    {
        return PelanggaranForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PelanggaranInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PelanggaransTable::configure($table);
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
            'index' => ListPelanggarans::route('/'),
            'create' => CreatePelanggaran::route('/create'),
            'view' => ViewPelanggaran::route('/{record}'),
            'edit' => EditPelanggaran::route('/{record}/edit'),
        ];
    }
}
