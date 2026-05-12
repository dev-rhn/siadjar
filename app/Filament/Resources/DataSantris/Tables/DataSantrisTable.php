<?php

namespace App\Filament\Resources\DataSantris\Tables;

// use Dom\Text;
use App\Filament\Exports\DataSantriExporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DataSantrisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_santri')
                    ->label('Foto Santri'),
                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jk')
                    ->label('Jenis Kelamin')
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Dhuafa'      => 'primary',
                        'Yatim'       => 'warning',
                        'Piatu'       => 'info',
                        'Yatim Piatu' => 'danger',
                        'Reguler'     => 'success',
                        default       => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('kamar.nama_kamar')
                    ->label('Kamar')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tahun_masuk')
                    ->label('Tahun Masuk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),
                TextColumn::make('jenjang')
                    ->label('Jenjang')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jenjang')
                    ->label('Filter Jenjang')
                    ->options([
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                    ]),
                SelectFilter::make('keterangan')
                    ->label('Filter Keterangan')
                    ->options([
                        'Dhuafa' => 'Dhuafa',
                        'Yatim' => 'Yatim',
                        'Piatu' => 'Piatu',
                        'Yatim Piatu' => 'Yatim Piatu',
                        'Reguler' => 'Reguler',
                    ]),
                SelectFilter::make('tahun_masuk')
                    ->label('Filter Tahun Masuk')
                    ->options(function () {
                        return \App\Models\DataSantri::query()
                            ->distinct()
                            ->orderBy('tahun_masuk', 'desc')
                            ->pluck('tahun_masuk', 'tahun_masuk')
                            ->toArray();
                    })
                    ->placeholder('Semua Tahun'),

                SelectFilter::make('kamar_id')
                    ->label('Filter Kamar')
                    ->relationship('kamar', 'nama_kamar')
                    ->options(function () {
                        return \App\Models\Kamar::pluck('nama_kamar', 'id')->toArray();
                    }),

                SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->options(function () {
                        return \App\Models\Kelas::pluck('nama_kelas', 'id')->toArray();
                    }),
                SelectFilter::make('jk')
                    ->label('Filter Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
            ],layout: FiltersLayout::AboveContent)->filtersFormColumns(3)
            ->recordActions([
                Action::make('view_pdf')
                        ->label('View PDF')
                        ->icon('heroicon-o-eye')
                        ->button()
                        ->color('primary')
                        // Memanggil route yang sudah kita buat di web.php
                        ->url(fn ($record) => route('santri.view-pdf', $record))
                        ->openUrlInNewTab(),

                Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->button()
                    ->color('success')
                    // Untuk download, kita bisa gunakan URL yang sama tapi kirim parameter download
                    ->url(fn ($record) => route('santri.view-pdf', ['santri' => $record, 'download' => 1]))
                    ->openUrlInNewTab(),
                ViewAction::make()
                    ->button()
                    ->color('info'),
                EditAction::make()
                    ->button()
                    ->color('warning'),
                DeleteAction::make()
                    ->button()
                    ->color('danger'),
            ])
            ->headerActions([

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                ExportAction::make()
                    ->exporter(DataSantriExporter::class)
                    ->label('Export Data')
                    ->color('info')
            ]);
    }
}
