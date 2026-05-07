<?php

namespace App\Filament\Widgets;

use App\Models\CatatanPelanggaran;
use App\Models\DataSantri;
use App\Models\Kepegawaian;
use App\Models\Kesehatan;
use App\Models\Surat;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuickAccessWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Surat Masuk', Surat::where('arah', 'Masuk')->count())
                ->description('Arsip surat masuk')
                ->descriptionIcon('heroicon-m-arrow-down-left')
                ->color('success')
                ->url(route('filament.admin.resources.surat.index', ['tableFilters[arah][value]' => 'Masuk'])),


            Stat::make('Surat Keluar', Surat::where('arah', 'Keluar')->count())
                ->description('Arsip surat keluar')
                ->descriptionIcon('heroicon-m-arrow-up-right')
                ->color('danger')
                ->url(route('filament.admin.resources.surat.index', ['tableFilters[arah][value]' => 'Keluar'])),

            Stat::make('Data Pegawai', Kepegawaian::count())
                ->description('Manajemen SDM & Jabatan')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('warning')
                ->url(route('filament.admin.resources.data-kepegawaian.index')),

            Stat::make('Data Santri', DataSantri::count())
                ->description('Kelola data santri')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->url(route('filament.admin.resources.data-santri.index')),
            
            Stat::make('Catatan Pelanggaran', CatatanPelanggaran::count())
                ->description('Rekam pelanggaran santri')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->url(route('filament.admin.resources.catatan-pelanggaran.index')),

            Stat::make('Catatan Kesehatan', Kesehatan::count())
                ->description('Rekam kesehatan santri')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info')
                ->url(route('filament.admin.resources.kesehatan.index')),
        ];  
    }
}
