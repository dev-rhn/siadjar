<?php

namespace App\Reports\Concerns;

use App\Models\DataSantri;
use App\Models\Kamar;
use App\Models\Kelas;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Field, aturan validasi, dan keterangan filter yang dipakai bersama oleh beberapa laporan.
 */
trait HasCommonFilters
{
    /**
     * @return array<int, DatePicker>
     */
    protected function periodeFields(string $label = 'Tanggal'): array
    {
        return [
            DatePicker::make('tanggal_dari')
                ->label($label.' dari')
                ->required()
                ->native(false)
                ->displayFormat('d/m/Y'),
            DatePicker::make('tanggal_sampai')
                ->label($label.' sampai')
                ->required()
                ->native(false)
                ->displayFormat('d/m/Y'),
        ];
    }

    /**
     * Rentang tanggal wajib, maksimal 1 tahun.
     *
     * @return array<string, mixed>
     */
    protected function periodeRules(): array
    {
        return [
            'tanggal_dari' => ['required', 'date_format:Y-m-d'],
            'tanggal_sampai' => ['required', 'date_format:Y-m-d', 'after_or_equal:tanggal_dari'],
        ];
    }

    /**
     * Dipanggil dari afterValidation(): tolak rentang tanggal lebih dari 1 tahun.
     */
    protected function validatePeriodeMaksimalSetahun(Validator $validator): void
    {
        if ($validator->errors()->hasAny(['tanggal_dari', 'tanggal_sampai'])) {
            return;
        }

        $data = $validator->getData();

        if (Carbon::parse($data['tanggal_dari'])->addYear()->lt(Carbon::parse($data['tanggal_sampai']))) {
            $validator->errors()->add('tanggal_sampai', 'Rentang tanggal maksimal 1 tahun.');
        }
    }

    /**
     * @return array<string, string>
     */
    protected function defaultPeriode(): array
    {
        return [
            'tanggal_dari' => now()->startOfMonth()->toDateString(),
            'tanggal_sampai' => now()->endOfMonth()->toDateString(),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    protected function describePeriode(array $filters): ?string
    {
        if (! isset($filters['tanggal_dari'], $filters['tanggal_sampai'])) {
            return null;
        }

        return $this->formatTanggal($filters['tanggal_dari']).' s.d. '.$this->formatTanggal($filters['tanggal_sampai']);
    }

    protected function formatTanggal(mixed $tanggal): ?string
    {
        return $tanggal ? Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') : null;
    }

    protected function kelasField(): Select
    {
        return Select::make('kelas_id')
            ->label('Kelas')
            ->options(fn () => Kelas::query()->orderBy('nama_kelas')->pluck('nama_kelas', 'id'))
            ->searchable()
            ->placeholder('Semua kelas');
    }

    protected function kamarField(): Select
    {
        return Select::make('kamar_id')
            ->label('Kamar')
            ->options(fn () => Kamar::query()->orderBy('nama_kamar')->pluck('nama_kamar', 'id'))
            ->searchable()
            ->placeholder('Semua kamar');
    }

    protected function santriField(): Select
    {
        return Select::make('data_santri_id')
            ->label('Santri')
            ->searchable()
            ->getSearchResultsUsing(fn (string $search) => DataSantri::query()
                ->where('nama', 'like', "%{$search}%")
                ->orderBy('nama')
                ->limit(50)
                ->pluck('nama', 'id'))
            ->getOptionLabelUsing(fn ($value) => DataSantri::find($value)?->nama)
            ->placeholder('Semua santri');
    }

    /**
     * @return array<string, mixed>
     */
    protected function kelasKamarSantriRules(): array
    {
        return [
            'kelas_id' => ['nullable', 'integer', Rule::exists('kelas', 'id')],
            'kamar_id' => ['nullable', 'integer', Rule::exists('kamars', 'id')],
            'data_santri_id' => ['nullable', 'integer', Rule::exists('data_santris', 'id')],
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, string|null>
     */
    protected function describeKelasKamarSantri(array $filters): array
    {
        return [
            'Kelas' => isset($filters['kelas_id']) ? Kelas::find($filters['kelas_id'])?->nama_kelas : null,
            'Kamar' => isset($filters['kamar_id']) ? Kamar::find($filters['kamar_id'])?->nama_kamar : null,
            'Santri' => isset($filters['data_santri_id']) ? DataSantri::find($filters['data_santri_id'])?->nama : null,
        ];
    }
}
