<?php

namespace App\Filament\Resources\LaporanArsips\Tables;

use App\Filament\Support\AksesLinkNotification;
use App\Models\LaporanArsip;
use App\Reports\ReportPdfGenerator;
use App\Reports\ReportRegistry;
use App\Reports\Support\SantriPreviewLink;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;

class LaporanArsipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('user')->withCount('aksesLogs'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('judul')
                    ->label('Laporan')
                    ->searchable()
                    ->sortable()
                    ->description(fn (LaporanArsip $record) => $record->dicetak_ulang_dari ? "Cetak ulang dari #{$record->dicetak_ulang_dari}" : null),
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
                TextColumn::make('status_akses')
                    ->label('Link Preview')
                    ->state(fn (LaporanArsip $record) => $record->statusAkses())
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        LaporanArsip::AKSES_AKTIF => 'success',
                        LaporanArsip::AKSES_KEDALUWARSA => 'warning',
                        LaporanArsip::AKSES_DICABUT => 'danger',
                        default => 'gray',
                    })
                    ->description(fn (LaporanArsip $record) => $record->aksesAktif()
                        ? 's.d. '.$record->akses_berlaku_sampai->translatedFormat('d M Y')
                        : null),
                IconColumn::make('punya_pin')
                    ->label('PIN')
                    ->state(fn (LaporanArsip $record) => $record->punyaPin())
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedLockClosed)
                    ->falseIcon(Heroicon::OutlinedMinus)
                    ->alignCenter(),
                TextColumn::make('akses_logs_count')
                    ->label('Dibuka')
                    ->numeric()
                    ->alignEnd()
                    ->suffix('x'),
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
                ActionGroup::make([
                    static::cetakUlangAction(),
                    static::perpanjangAction(),
                    static::resetPinAction(),
                    static::riwayatAksesAction(),
                    static::cabutAksesAction(),
                ])->label('Akses')->button()->color('gray'),
            ])
            ->toolbarActions([]);
    }

    protected static function cabutAksesAction(): Action
    {
        return Action::make('cabutAkses')
            ->label('Cabut akses')
            ->icon(Heroicon::OutlinedNoSymbol)
            ->color('danger')
            ->visible(fn (LaporanArsip $record) => $record->punyaLinkPreview() && ! $record->aksesDicabut())
            ->requiresConfirmation()
            ->modalHeading('Cabut akses link preview?')
            ->modalDescription('Semua link santri di PDF ini langsung tidak bisa dibuka. Tindakan ini tidak bisa dibatalkan; gunakan "Cetak ulang" untuk membuat PDF dengan link baru.')
            ->action(function (LaporanArsip $record): void {
                $record->update(['akses_dicabut_at' => now()]);

                Notification::make()->title('Akses link dicabut')->success()->send();
            });
    }

    protected static function perpanjangAction(): Action
    {
        return Action::make('perpanjangAkses')
            ->label('Perpanjang '.SantriPreviewLink::masaBerlakuHari().' hari')
            ->icon(Heroicon::OutlinedClock)
            ->visible(fn (LaporanArsip $record) => $record->punyaLinkPreview() && ! $record->aksesDicabut())
            ->requiresConfirmation()
            ->modalDescription('Link di PDF ini akan berlaku '.SantriPreviewLink::masaBerlakuHari().' hari lagi terhitung hari ini.')
            ->action(function (LaporanArsip $record): void {
                $record->update(['akses_berlaku_sampai' => now()->addDays(SantriPreviewLink::masaBerlakuHari())]);

                Notification::make()
                    ->title('Akses diperpanjang sampai '.$record->akses_berlaku_sampai->locale('id')->translatedFormat('d F Y'))
                    ->success()
                    ->send();
            });
    }

    protected static function resetPinAction(): Action
    {
        return Action::make('resetPin')
            ->label('Reset PIN')
            ->icon(Heroicon::OutlinedKey)
            ->visible(fn (LaporanArsip $record) => $record->punyaPin() && ! $record->aksesDicabut())
            ->requiresConfirmation()
            ->modalDescription('PIN lama langsung tidak berlaku. PIN baru ditampilkan sekali.')
            ->action(function (LaporanArsip $record): void {
                $pin = SantriPreviewLink::buatPin();
                $record->update(['akses_pin_hash' => Hash::make($pin)]);

                AksesLinkNotification::pin($pin);
            });
    }

    protected static function cetakUlangAction(): Action
    {
        return Action::make('cetakUlang')
            ->label('Cetak ulang dengan link baru')
            ->icon(Heroicon::OutlinedArrowPath)
            ->visible(fn (LaporanArsip $record) => ReportRegistry::find($record->report_slug) !== null)
            ->modalHeading('Cetak ulang laporan')
            ->modalDescription('PDF baru dibuat dengan filter dan orientasi yang sama, memakai data terkini, dan link baru yang berlaku '.SantriPreviewLink::masaBerlakuHari().' hari.')
            ->schema([
                Toggle::make('pin')
                    ->label('Lindungi dengan PIN')
                    ->default(fn (LaporanArsip $record) => $record->punyaPin()),
                Toggle::make('cabut_lama')
                    ->label('Cabut akses PDF lama')
                    ->default(true)
                    ->visible(fn (LaporanArsip $record) => $record->aksesAktif()),
            ])
            ->action(function (LaporanArsip $record, array $data, ReportPdfGenerator $generator): void {
                $report = ReportRegistry::findOrFail($record->report_slug);

                try {
                    $filters = $report->validateFilters($record->filters ?? []);
                } catch (ValidationException $exception) {
                    Notification::make()
                        ->title('Filter laporan lama tidak valid lagi')
                        ->body(collect($exception->errors())->flatten()->implode(' '))
                        ->danger()
                        ->send();

                    return;
                }

                if (($rowCount = $report->count($filters)) > ($maxRows = (int) config('laporan.max_baris'))) {
                    Notification::make()
                        ->title('Data terlalu banyak')
                        ->body("Jumlah data ({$rowCount}) melebihi batas {$maxRows} baris.")
                        ->danger()
                        ->send();

                    return;
                }

                $pin = ! empty($data['pin']) ? SantriPreviewLink::buatPin() : null;

                $arsipBaru = $generator->generate(
                    $report,
                    $filters,
                    $record->orientasi,
                    auth()->user(),
                    denganLink: true,
                    pin: $pin,
                    cetakUlangDari: $record,
                )->arsip;

                if (! empty($data['cabut_lama']) && $record->aksesAktif()) {
                    $record->update(['akses_dicabut_at' => now()]);
                }

                $urlPdf = route('laporan.arsip.unduh', ['laporanArsip' => $arsipBaru, 'lihat' => 1]);

                $pin ? AksesLinkNotification::pin($pin, $urlPdf) : AksesLinkNotification::pdfSiap($urlPdf);
            });
    }

    protected static function riwayatAksesAction(): Action
    {
        return Action::make('riwayatAkses')
            ->label('Riwayat akses')
            ->icon(Heroicon::OutlinedListBullet)
            ->visible(fn (LaporanArsip $record) => $record->punyaLinkPreview())
            ->modalHeading('Riwayat akses link preview')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            ->modalContent(fn (LaporanArsip $record) => view('filament.laporan-arsip.riwayat-akses', [
                'logs' => $record->aksesLogs()->with('dataSantri:id,nama')->latest('created_at')->limit(200)->get(),
                'total' => $record->aksesLogs()->count(),
            ]));
    }
}
