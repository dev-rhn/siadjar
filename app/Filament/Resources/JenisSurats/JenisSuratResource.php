<?php

namespace App\Filament\Resources\JenisSurats;

use App\Filament\Resources\JenisSurats\Pages\CreateJenisSurat;
use App\Filament\Resources\JenisSurats\Pages\EditJenisSurat;
use App\Filament\Resources\JenisSurats\Pages\ListJenisSurats;
use App\Filament\Resources\JenisSurats\Pages\ViewJenisSurat;
use App\Filament\Resources\JenisSurats\Schemas\JenisSuratForm;
use App\Filament\Resources\JenisSurats\Schemas\JenisSuratInfolist;
use App\Filament\Resources\JenisSurats\Tables\JenisSuratsTable;
use App\Models\JenisSurat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class JenisSuratResource extends Resource
{
    protected static ?string $model = JenisSurat::class;

    protected static string|UnitEnum|null $navigationGroup = 'Administrasi';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'nama_jenis';
    
    protected static ?string $navigationLabel = 'Jenis Surat';
    
    protected static ?string $pluralModelLabel = 'Jenis Surat';
    
    protected static ?string $modelLabel = 'Jenis Surat';

    public static function form(Schema $schema): Schema
    {
        return JenisSuratForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JenisSuratInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JenisSuratsTable::configure($table);
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
            'index' => ListJenisSurats::route('/'),
            'create' => CreateJenisSurat::route('/create'),
            'view' => ViewJenisSurat::route('/{record}'),
            'edit' => EditJenisSurat::route('/{record}/edit'),
        ];
    }
}
