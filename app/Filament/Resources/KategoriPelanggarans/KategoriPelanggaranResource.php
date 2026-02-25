<?php

namespace App\Filament\Resources\KategoriPelanggarans;

use App\Filament\Resources\KategoriPelanggarans\Pages\CreateKategoriPelanggaran;
use App\Filament\Resources\KategoriPelanggarans\Pages\EditKategoriPelanggaran;
use App\Filament\Resources\KategoriPelanggarans\Pages\ListKategoriPelanggarans;
use App\Filament\Resources\KategoriPelanggarans\Pages\ViewKategoriPelanggaran;
use App\Filament\Resources\KategoriPelanggarans\Schemas\KategoriPelanggaranForm;
use App\Filament\Resources\KategoriPelanggarans\Schemas\KategoriPelanggaranInfolist;
use App\Filament\Resources\KategoriPelanggarans\Tables\KategoriPelanggaransTable;
use App\Models\KategoriPelanggaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class KategoriPelanggaranResource extends Resource
{
    protected static ?string $model = KategoriPelanggaran::class;

    protected static string|UnitEnum|null $navigationGroup = 'Kepengasuhan';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Kategori Pelanggaran';

    protected static ?string $slug = 'kategori-pelanggaran';

    protected static ?string $pluralModelLabel = 'Kategori Pelanggaran';
    
    protected static ?string $modelLabel = 'Kategori Pelanggaran';

    public static function form(Schema $schema): Schema
    {
        return KategoriPelanggaranForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KategoriPelanggaranInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategoriPelanggaransTable::configure($table);
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
            'index' => ListKategoriPelanggarans::route('/'),
            'create' => CreateKategoriPelanggaran::route('/create'),
            'view' => ViewKategoriPelanggaran::route('/{record}'),
            'edit' => EditKategoriPelanggaran::route('/{record}/edit'),
        ];
    }
}
