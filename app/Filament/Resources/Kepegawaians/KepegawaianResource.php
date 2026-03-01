<?php

namespace App\Filament\Resources\Kepegawaians;

use App\Filament\Resources\Kepegawaians\Pages\CreateKepegawaian;
use App\Filament\Resources\Kepegawaians\Pages\EditKepegawaian;
use App\Filament\Resources\Kepegawaians\Pages\ListKepegawaians;
use App\Filament\Resources\Kepegawaians\Pages\ViewKepegawaian;
use App\Filament\Resources\Kepegawaians\Schemas\KepegawaianForm;
use App\Filament\Resources\Kepegawaians\Schemas\KepegawaianInfolist;
use App\Filament\Resources\Kepegawaians\Tables\KepegawaiansTable;
use App\Models\Kepegawaian;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class KepegawaianResource extends Resource
{
    protected static ?string $model = Kepegawaian::class;
    
    protected static string|UnitEnum|null $navigationGroup = 'Kepegawaian';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'nama_pegawai';
    
    protected static ?string $navigationLabel = 'Data Kepegawaian';
    
    protected static ?string $slug = 'data-kepegawaian';
    
    protected static ?string $pluralModelLabel = 'Data Kepegawaian';
    
    protected static ?string $modelLabel = 'Data Kepegawaian';

    public static function form(Schema $schema): Schema
    {
        return KepegawaianForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KepegawaianInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KepegawaiansTable::configure($table);
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
            'index' => ListKepegawaians::route('/'),
            'create' => CreateKepegawaian::route('/create'),
            'view' => ViewKepegawaian::route('/{record}'),
            'edit' => EditKepegawaian::route('/{record}/edit'),
        ];
    }
}
