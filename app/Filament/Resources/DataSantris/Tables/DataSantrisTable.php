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
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jk')
                    ->label('Jenis Kelamin')
                    ->sortable(),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50)
                    ->wrap(),
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
                    ->url(function ($record) {
                        return route('santri.view-pdf', $record->id);
                    })
                    ->openUrlInNewTab(),
                Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->button()
                    ->color('success')
                    ->action(function ($record) {
                        $record->load([
                            'kesehatan',
                            'catatanPelanggarans.pelanggaran.kategoriPelanggaran',
                            'kamar',
                            'kelas',
                        ]);

                        // Convert foto ke base64
                        $fotoBase64 = null;
                        if ($record->foto_santri) {
                            $fullPath = storage_path('app/public/' . $record->foto_santri);
                            if (file_exists($fullPath)) {
                                $fotoMime = mime_content_type($fullPath);
                                $fotoBase64 = 'data:' . $fotoMime . ';base64,' . base64_encode(file_get_contents($fullPath));
                            }
                        }

                        $pdf = Pdf::loadView('pdf.santri', [
                            'santri'     => $record,
                            'fotoBase64' => $fotoBase64,
                        ])->setPaper('a4', 'portrait');

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "santri-{$record->nisn}-{$record->nama}.pdf"
                        );
                    }),
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
