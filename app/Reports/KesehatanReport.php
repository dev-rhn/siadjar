<?php

namespace App\Reports;

use App\Models\Kesehatan;
use App\Reports\Concerns\HasCommonFilters;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class KesehatanReport extends BaseReport
{
    use HasCommonFilters;

    private const STATUS = [
        'belum' => 'Belum sembuh',
        'sembuh' => 'Sembuh',
    ];

    public static function slug(): string
    {
        return 'kesehatan';
    }

    public function title(): string
    {
        return 'Rekap Kesehatan Santri';
    }

    public function description(): ?string
    {
        return 'Daftar santri sakit per periode beserta penyakit, obat, dan status kesembuhan.';
    }

    public function defaultOrientation(): string
    {
        return 'landscape';
    }

    public function defaultFilters(): array
    {
        return $this->defaultPeriode();
    }

    public function filterSchema(): array
    {
        return [
            ...$this->periodeFields('Tanggal sakit'),
            Select::make('status')
                ->label('Status')
                ->options(self::STATUS)
                ->placeholder('Semua status'),
            $this->kelasField(),
            $this->kamarField(),
            $this->santriField(),
        ];
    }

    public function rules(): array
    {
        return [
            ...$this->periodeRules(),
            ...$this->kelasKamarSantriRules(),
            'status' => ['nullable', Rule::in(array_keys(self::STATUS))],
        ];
    }

    protected function afterValidation(Validator $validator): void
    {
        $this->validatePeriodeMaksimalSetahun($validator);
    }

    public function query(array $filters): Builder
    {
        return Kesehatan::query()
            ->with(['dataSantri.kelas', 'dataSantri.kamar'])
            ->whereBetween('tanggal_sakit', [$filters['tanggal_dari'], $filters['tanggal_sampai']])
            ->when($filters['status'] ?? null, fn (Builder $query, $value) => $query->where('is_sembuh', $value === 'sembuh'))
            ->when($filters['data_santri_id'] ?? null, fn (Builder $query, $value) => $query->where('data_santri_id', $value))
            ->when($filters['kelas_id'] ?? null, fn (Builder $query, $value) => $query
                ->whereHas('dataSantri', fn (Builder $query) => $query->where('kelas_id', $value)))
            ->when($filters['kamar_id'] ?? null, fn (Builder $query, $value) => $query
                ->whereHas('dataSantri', fn (Builder $query) => $query->where('kamar_id', $value)))
            ->orderBy('tanggal_sakit')
            ->orderBy('id');
    }

    public function columns(): array
    {
        return [
            'tanggal' => ['label' => 'Tanggal Sakit', 'width' => '11%'],
            'nama' => ['label' => 'Nama Santri', 'width' => '17%', 'link' => true],
            'kelas' => ['label' => 'Kelas', 'width' => '8%'],
            'kamar' => ['label' => 'Kamar', 'width' => '8%'],
            'penyakit' => ['label' => 'Penyakit', 'width' => '15%'],
            'obat' => ['label' => 'Obat', 'width' => '14%'],
            'status' => ['label' => 'Status', 'width' => '9%', 'align' => 'center'],
            'keterangan' => ['label' => 'Keterangan', 'width' => '14%'],
        ];
    }

    /**
     * @param  Kesehatan  $record
     */
    public function row(Model $record): array
    {
        return [
            'tanggal' => $this->formatTanggal($record->tanggal_sakit),
            'nama' => $record->dataSantri?->nama,
            '_santri_id' => $record->data_santri_id,
            'kelas' => $record->dataSantri?->kelas?->nama_kelas,
            'kamar' => $record->dataSantri?->kamar?->nama_kamar,
            'penyakit' => $record->nama_penyakit,
            'obat' => $record->obat,
            'status' => $record->is_sembuh ? self::STATUS['sembuh'] : self::STATUS['belum'],
            'keterangan' => $record->keterangan,
            '_class' => $record->is_sembuh ? null : 'sorot',
        ];
    }

    public function summary(Collection $records, array $filters): array
    {
        $penyakitTerbanyak = $records
            ->countBy(fn (Kesehatan $record) => Str::title(trim($record->nama_penyakit)))
            ->sortDesc()
            ->take(5)
            ->all();

        return array_filter([
            'Total' => [
                'Kasus' => $records->count(),
                'Santri' => $records->pluck('data_santri_id')->unique()->count(),
            ],
            'Status' => [
                self::STATUS['sembuh'] => $records->where('is_sembuh', true)->count(),
                self::STATUS['belum'] => $records->where('is_sembuh', false)->count(),
            ],
            'Penyakit Terbanyak' => $penyakitTerbanyak,
        ]);
    }

    public function describeFilters(array $filters): array
    {
        return array_filter([
            'Periode' => $this->describePeriode($filters),
            'Status' => self::STATUS[$filters['status'] ?? ''] ?? null,
            ...$this->describeKelasKamarSantri($filters),
        ]);
    }

    protected function validationAttributes(): array
    {
        return [
            'tanggal_dari' => 'tanggal dari',
            'tanggal_sampai' => 'tanggal sampai',
            'kelas_id' => 'kelas',
            'kamar_id' => 'kamar',
            'data_santri_id' => 'santri',
        ];
    }
}
