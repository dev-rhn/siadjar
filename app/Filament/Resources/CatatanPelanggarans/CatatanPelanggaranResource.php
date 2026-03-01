<?php

namespace App\Filament\Resources\CatatanPelanggarans;

use App\Filament\Resources\CatatanPelanggarans\Pages\CreateCatatanPelanggaran;
use App\Filament\Resources\CatatanPelanggarans\Pages\EditCatatanPelanggaran;
use App\Filament\Resources\CatatanPelanggarans\Pages\ListCatatanPelanggarans;
use App\Filament\Resources\CatatanPelanggarans\Pages\ViewCatatanPelanggaran;
use App\Filament\Resources\CatatanPelanggarans\Schemas\CatatanPelanggaranForm;
use App\Filament\Resources\CatatanPelanggarans\Schemas\CatatanPelanggaranInfolist;
use App\Filament\Resources\CatatanPelanggarans\Tables\CatatanPelanggaransTable;
use App\Models\CatatanPelanggaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CatatanPelanggaranResource extends Resource
{
    protected static ?string $model = CatatanPelanggaran::class;

    protected static string|UnitEnum|null $navigationGroup = 'Kepengasuhan';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Catatan Pelanggaran';

    protected static ?string $slug = 'catatan-pelanggaran';

    protected static ?string $pluralModelLabel = 'Catatan Pelanggaran';
    
    protected static ?string $modelLabel = 'Catatan Pelanggaran';


    public static function form(Schema $schema): Schema
    {
        return CatatanPelanggaranForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CatatanPelanggaranInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CatatanPelanggaransTable::configure($table);
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
            'index' => ListCatatanPelanggarans::route('/'),
            // 'create' => CreateCatatanPelanggaran::route('/create'),
            // 'view' => ViewCatatanPelanggaran::route('/{record}'),
            // 'edit' => EditCatatanPelanggaran::route('/{record}/edit'),
        ];
    }
}
