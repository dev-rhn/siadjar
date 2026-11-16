<?php

namespace App\Filament\Resources\LaporanArsips\Tables;

use App\Models\LaporanArsip;
use App\Reports\ReportRegistry;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class LaporanArsipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('user'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('judul')
                    ->label('Laporan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('keterangan_filter')
                    ->label('Filter')
                    ->wrap()
                    ->limit(80)
                    ->tooltip(fn (LaporanArsip $record) => $record->keterangan_filter),
                TextColumn::make('jumlah_baris')
                    ->label('Jumlah Data')
                    ->numeric()
                    ->alignEnd()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Dicetak Oleh')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Waktu Cetak')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('file_size')
                    ->label('Ukuran')
                    ->formatStateUsing(fn (int $state) => Number::fileSize($state, precision: 1))
                    ->alignEnd()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('report_slug')
                    ->label('Jenis Laporan')
                    ->options(fn () => ReportRegistry::options()),
                Filter::make('created_at')
                    ->label('Tanggal Cetak')
                    ->schema([
                        DatePicker::make('dari')->label('Dari tanggal'),
                        DatePicker::make('sampai')->label('Sampai tanggal'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['dari'] ?? null, fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date))
                        ->when($data['sampai'] ?? null, fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date))),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon(Heroicon::OutlinedEye)
                    ->button()
                    ->color('info')
                    ->url(fn (LaporanArsip $record) => route('laporan.arsip.unduh', ['laporanArsip' => $record, 'lihat' => 1]))
                    ->openUrlInNewTab(),
                Action::make('unduh')
                    ->label('Unduh')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->button()
                    ->color('gray')
                    ->url(fn (LaporanArsip $record) => route('laporan.arsip.unduh', $record)),
            ])
            ->toolbarActions([]);
    }
}
