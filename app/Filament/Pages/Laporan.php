<?php

namespace App\Filament\Pages;

use App\Reports\BaseReport;
use App\Reports\ReportRegistry;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Js;
use Illuminate\Validation\ValidationException;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class Laporan extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Cetak Laporan';

    protected static ?string $title = 'Cetak Laporan';

    protected static ?string $slug = 'laporan';

    protected static ?int $navigationSort = 1;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $slug = array_key_first(ReportRegistry::options());
        $report = ReportRegistry::find($slug);

        $this->form->fill([
            'report' => $slug,
            'orientasi' => $report?->defaultOrientation() ?? 'portrait',
            'filters' => $report?->defaultFilters() ?? [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Jenis Laporan')
                    ->schema([
                        Select::make('report')
                            ->label('Laporan')
                            ->options(fn () => ReportRegistry::options())
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $report = ReportRegistry::find($state);
                                $set('filters', $report?->defaultFilters() ?? []);
                                $set('orientasi', $report?->defaultOrientation() ?? 'portrait');
                            })
                            ->helperText(fn (Get $get) => ReportRegistry::find($get('report'))?->description()),
                        Select::make('orientasi')
                            ->label('Orientasi Kertas (A4)')
                            ->options(BaseReport::ORIENTATIONS)
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),
                Section::make('Filter')
                    ->description('Kosongkan filter untuk menampilkan semua data.')
                    ->schema(fn (Get $get): array => ReportRegistry::find($get('report'))?->filterSchema() ?? [])
                    ->statePath('filters')
                    ->columns(3)
                    ->key('filters-section'),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->footer([
                    Actions::make([
                        $this->bukaPdfAction(),
                        $this->unduhPdfAction(),
                    ])->key('form-actions'),
                ]),
        ]);
    }

    public function bukaPdfAction(): Action
    {
        return Action::make('bukaPdf')
            ->label('Cetak PDF')
            ->icon(Heroicon::OutlinedPrinter)
            ->action(function (): void {
                $url = $this->buildPdfUrl();

                if ($url) {
                    $this->js('window.open('.Js::from($url).', "_blank")');
                }
            });
    }

    public function unduhPdfAction(): Action
    {
        return Action::make('unduhPdf')
            ->label('Unduh PDF')
            ->icon(Heroicon::OutlinedArrowDownTray)
            ->color('gray')
            ->action(function (): void {
                $url = $this->buildPdfUrl(download: true);

                if ($url) {
                    $this->redirect($url);
                }
            });
    }

    /**
     * Validasi form lalu bangun URL PDF. Mengembalikan null jika data melebihi batas.
     */
    protected function buildPdfUrl(bool $download = false): ?string
    {
        $state = $this->form->getState();
        $report = ReportRegistry::findOrFail($state['report'] ?? null);

        try {
            $filters = $report->validateFilters($state['filters'] ?? []);
        } catch (ValidationException $exception) {
            throw ValidationException::withMessages(
                collect($exception->errors())
                    ->mapWithKeys(fn (array $messages, string $key) => ["data.filters.{$key}" => $messages])
                    ->all(),
            );
        }

        $rowCount = $report->count($filters);
        $maxRows = (int) config('laporan.max_baris');

        if ($rowCount > $maxRows) {
            Notification::make()
                ->title('Data terlalu banyak')
                ->body("Jumlah data ({$rowCount}) melebihi batas {$maxRows} baris. Persempit filter laporan.")
                ->danger()
                ->send();

            return null;
        }

        return route('laporan.pdf', array_filter([
            'slug' => $report::slug(),
            'orientasi' => $state['orientasi'],
            'filters' => $filters ?: null,
            'unduh' => $download ? 1 : null,
        ]));
    }
}
