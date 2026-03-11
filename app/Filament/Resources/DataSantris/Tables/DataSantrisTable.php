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
                TextColumn::make('asal_sekolah')
                    ->label('Asal Sekolah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),
                TextColumn::make('tahun_masuk')
                    ->label('Tahun Masuk')
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
                //
            ])
            ->recordActions([
                Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($record) {
                        $record->load([
                            'kesehatan',
                            'catatanPelanggarans.pelanggaran.kategoriPelanggaran'
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
