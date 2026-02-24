<?php

namespace App\Filament\Resources\UserManagement;

use App\Filament\Resources\UserManagement\Pages\CreateUserManagement;
use App\Filament\Resources\UserManagement\Pages\EditUserManagement;
use App\Filament\Resources\UserManagement\Pages\ListUserManagement;
use App\Filament\Resources\UserManagement\Schemas\UserManagementForm;
use App\Filament\Resources\UserManagement\Tables\UserManagementTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class UserManagementResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|UnitEnum|null $navigationGroup = 'Kepegawaian';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $slug = 'pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';
    
    protected static ?string $modelLabel = 'Pengguna';

    public static function form(Schema $schema): Schema
    {
        return UserManagementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserManagementTable::configure($table);
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
            'index' => ListUserManagement::route('/'),
            'create' => CreateUserManagement::route('/create'),
            'edit' => EditUserManagement::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
