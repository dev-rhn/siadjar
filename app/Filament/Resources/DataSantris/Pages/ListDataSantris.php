<?php

namespace App\Filament\Resources\DataSantris\Pages;

use App\Filament\Resources\DataSantris\DataSantriResource;
use App\Filament\Resources\DataSantris\Widgets\DataSantriStats;
use App\Imports\DataSantriImport;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDataSantris extends ListRecords
{
    protected static string $resource = DataSantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->use(DataSantriImport::class)
                ->label('Import Data')
                ->color('warning')
                ->beforeImport(function ($data, $excelImportAction){
                    if (count($data) > 1000) {
                        $excelImportAction->cancel();
                        Notification::make()
                            ->danger()
                            ->title('Jumlah data melebihi batas (maksimal 1000)')
                            ->send();
                        return false;
                    }

                // Tampilkan notifikasi proses import dimulai
                Notification::make()
                    ->info()
                    ->title('Memulai proses import data nasabah...')
                    ->send();
                return true;
                })
                ->afterImport(function ($data) {
                    // Tampilkan notifikasi proses import selesai
                    Notification::make()
                        ->success()
                        ->title('Proses import data santri selesai!')
                        ->send();
                }),
                CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DataSantriStats::class,
        ];
    }
}
