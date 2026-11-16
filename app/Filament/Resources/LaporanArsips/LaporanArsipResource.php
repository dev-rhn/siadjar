<?php

namespace App\Filament\Resources\LaporanArsips;

use App\Filament\Resources\LaporanArsips\Pages\ListLaporanArsips;
use App\Filament\Resources\LaporanArsips\Tables\LaporanArsipsTable;
use App\Models\LaporanArsip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class LaporanArsipResource extends Resource
{
    protected static ?string $model = LaporanArsip::class;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'judul';

    protected static ?string $navigationLabel = 'Arsip Laporan';

    protected static ?string $slug = 'arsip-laporan';

    protected static ?string $pluralModelLabel = 'Arsip Laporan';

    protected static ?string $modelLabel = 'Arsip Laporan';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return LaporanArsipsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLaporanArsips::route('/'),
        ];
    }
}
