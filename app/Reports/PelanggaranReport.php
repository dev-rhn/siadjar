<?php

namespace App\Reports;

use App\Models\CatatanPelanggaran;
use App\Models\KategoriPelanggaran;
use App\Reports\Concerns\HasCommonFilters;
use App\Reports\Support\StatusPoin;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class PelanggaranReport extends BaseReport
{
    use HasCommonFilters;

    public static function slug(): string
    {
        return 'pelanggaran';
    }

    public function title(): string
    {
        return 'Rekap Pelanggaran Santri';
    }

    public function description(): ?string
    {
        return 'Daftar pelanggaran per periode beserta total poin dan status SP per santri.';
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
            ...$this->periodeFields('Tanggal kejadian'),
            Select::make('kategori_pelanggaran_id')
                ->label('Kategori')
                ->options(fn () => KategoriPelanggaran::query()->orderBy('nama_kategori')->pluck('nama_kategori', 'id'))
                ->placeholder('Semua kategori'),
            $this->kelasField(),
            $this->kamarField(),
            $this->santriField(),
            Toggle::make('hanya_sp')
                ->label('Hanya santri berstatus SP')
                ->helperText('Ambang: '.StatusPoin::keterangan().' (total poin dalam periode).')
                ->inline(false),
        ];
    }

    public function rules(): array
    {
        return [
            ...$this->periodeRules(),
            ...$this->kelasKamarSantriRules(),
            'kategori_pelanggaran_id' => ['nullable', 'integer', Rule::exists('kategori_pelanggarans', 'id')],
            'hanya_sp' => ['nullable', 'boolean'],
        ];
    }

    protected function afterValidation(Validator $validator): void
    {
        $this->validatePeriodeMaksimalSetahun($validator);
    }

    public function query(array $filters): Builder
    {
        $query = $this->baseQuery($filters)
            ->with(['dataSantri.kelas', 'dataSantri.kamar', 'pelanggaran.kategoriPelanggaran'])
            ->orderBy('tanggal_kejadian')
            ->orderBy('id');

        if (! empty($filters['hanya_sp'])) {
            $santriSp = $this->baseQuery($filters)
                ->join('pelanggarans', 'pelanggarans.id', '=', 'catatan_pelanggarans.pelanggaran_id')
                ->groupBy('catatan_pelanggarans.data_santri_id')
                ->havingRaw('SUM(pelanggarans.poin) >= ?', [StatusPoin::minimumSp()])
                ->select('catatan_pelanggarans.data_santri_id');

            $query->whereIn('catatan_pelanggarans.data_santri_id', $santriSp);
        }

        return $query;
    }

    public function columns(): array
    {
        return [
            'tanggal' => ['label' => 'Tanggal', 'width' => '11%'],
            'nama' => ['label' => 'Nama Santri', 'width' => '17%'],
            'kelas' => ['label' => 'Kelas', 'width' => '8%'],
            'kamar' => ['label' => 'Kamar', 'width' => '8%'],
            'pelanggaran' => ['label' => 'Pelanggaran', 'width' => '18%'],
            'kategori' => ['label' => 'Kategori', 'width' => '11%'],
            'poin' => ['label' => 'Poin', 'width' => '5%', 'align' => 'center'],
            'catatan' => ['label' => 'Catatan', 'width' => '18%'],
        ];
    }

    /**
     * @param  CatatanPelanggaran  $record
     */
    public function row(Model $record): array
    {
        return [
            'tanggal' => $this->formatTanggal($record->tanggal_kejadian),
            'nama' => $record->dataSantri?->nama,
            'kelas' => $record->dataSantri?->kelas?->nama_kelas,
            'kamar' => $record->dataSantri?->kamar?->nama_kamar,
            'pelanggaran' => $record->pelanggaran?->nama_pelanggaran,
            'kategori' => $record->pelanggaran?->kategoriPelanggaran?->nama_kategori,
            'poin' => $record->pelanggaran?->poin ?? 0,
            'catatan' => $record->catatan,
        ];
    }

    public function summary(Collection $records, array $filters): array
    {
        $rekap = $this->rekapPerSantri($records);
        $perStatus = $rekap->countBy('status');

        $statusCounts = collect(StatusPoin::ambang())
            ->keys()
            ->sort()
            ->mapWithKeys(fn (string $status) => [$status => $perStatus->get($status, 0)])
            ->all();

        return [
            'Total' => [
                'Kejadian' => $records->count(),
                'Total poin' => $records->sum(fn (CatatanPelanggaran $record) => $record->pelanggaran?->poin ?? 0),
                'Santri' => $rekap->count(),
            ],
            'Status Santri' => [
                StatusPoin::AMAN => $perStatus->get(StatusPoin::AMAN, 0),
                ...$statusCounts,
            ],
        ];
    }

    public function tables(Collection $records, array $filters): array
    {
        return [[
            'title' => 'Rekap Poin per Santri ('.StatusPoin::keterangan().')',
            'columns' => [
                'nama' => ['label' => 'Nama Santri', 'width' => '35%'],
                'kelas' => ['label' => 'Kelas', 'width' => '15%'],
                'kamar' => ['label' => 'Kamar', 'width' => '15%'],
                'kejadian' => ['label' => 'Kejadian', 'width' => '10%', 'align' => 'center'],
                'total_poin' => ['label' => 'Total Poin', 'width' => '10%', 'align' => 'center'],
                'status' => ['label' => 'Status', 'width' => '11%', 'align' => 'center'],
            ],
            'rows' => $this->rekapPerSantri($records)
                ->map(fn (array $row) => [
                    ...$row,
                    '_class' => $row['status'] === StatusPoin::AMAN ? null : 'sorot '.strtolower($row['status']),
                ])
                ->all(),
        ]];
    }

    public function describeFilters(array $filters): array
    {
        return array_filter([
            'Periode' => $this->describePeriode($filters),
            'Kategori' => isset($filters['kategori_pelanggaran_id'])
                ? KategoriPelanggaran::find($filters['kategori_pelanggaran_id'])?->nama_kategori
                : null,
            ...$this->describeKelasKamarSantri($filters),
            'Status' => ! empty($filters['hanya_sp']) ? 'Hanya santri berstatus SP' : null,
        ]);
    }

    protected function validationAttributes(): array
    {
        return [
            'tanggal_dari' => 'tanggal dari',
            'tanggal_sampai' => 'tanggal sampai',
            'kategori_pelanggaran_id' => 'kategori',
            'kelas_id' => 'kelas',
            'kamar_id' => 'kamar',
            'data_santri_id' => 'santri',
        ];
    }

    /**
     * Query dasar tanpa filter "hanya SP", dipakai juga untuk menghitung total poin per santri.
     *
     * @param  array<string, mixed>  $filters
     */
    private function baseQuery(array $filters): Builder
    {
        return CatatanPelanggaran::query()
            ->whereBetween('catatan_pelanggarans.tanggal_kejadian', [$filters['tanggal_dari'], $filters['tanggal_sampai']])
            ->when($filters['data_santri_id'] ?? null, fn (Builder $query, $value) => $query->where('catatan_pelanggarans.data_santri_id', $value))
            ->when($filters['kategori_pelanggaran_id'] ?? null, fn (Builder $query, $value) => $query
                ->whereHas('pelanggaran', fn (Builder $query) => $query->where('kategori_pelanggaran_id', $value)))
            ->when($filters['kelas_id'] ?? null, fn (Builder $query, $value) => $query
                ->whereHas('dataSantri', fn (Builder $query) => $query->where('kelas_id', $value)))
            ->when($filters['kamar_id'] ?? null, fn (Builder $query, $value) => $query
                ->whereHas('dataSantri', fn (Builder $query) => $query->where('kamar_id', $value)));
    }

    /**
     * Total poin & status SP per santri, urut dari poin tertinggi.
     *
     * @param  Collection<int, CatatanPelanggaran>  $records
     * @return Collection<int, array{nama: string|null, kelas: string|null, kamar: string|null, kejadian: int, total_poin: int, status: string}>
     */
    private function rekapPerSantri(Collection $records): Collection
    {
        return $records
            ->groupBy('data_santri_id')
            ->map(function (Collection $items) {
                $santri = $items->first()->dataSantri;
                $totalPoin = (int) $items->sum(fn (CatatanPelanggaran $record) => $record->pelanggaran?->poin ?? 0);

                return [
                    'nama' => $santri?->nama,
                    'kelas' => $santri?->kelas?->nama_kelas,
                    'kamar' => $santri?->kamar?->nama_kamar,
                    'kejadian' => $items->count(),
                    'total_poin' => $totalPoin,
                    'status' => StatusPoin::for($totalPoin),
                ];
            })
            ->sortBy([['total_poin', 'desc'], ['nama', 'asc']])
            ->values();
    }
}
