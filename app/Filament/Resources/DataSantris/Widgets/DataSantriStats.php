<?php

namespace App\Filament\Resources\DataSantris\Widgets;

use App\Models\DataSantri;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DataSantriStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Santri Laki-laki', DataSantri::where('jk', 'Laki-laki')->count())
                ->description('Jumlah Santri Putra')
                ->descriptionIcon('heroicon-m-user')
                ->color('warning'),

            Stat::make('Total Santri Perempuan', DataSantri::where('jk', 'Perempuan')->count())
                ->description('Jumlah Santri Putri')
                ->descriptionIcon('heroicon-m-user')
                ->color('danger'),

            Stat::make('Santri Aktif', DataSantri::where('status', 'Aktif')->count())
                ->description('Status Santri aktif')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        
        ];
    }
}
