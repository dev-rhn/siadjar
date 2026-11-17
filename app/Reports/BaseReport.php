<?php

namespace App\Reports;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator as ValidatorInstance;

abstract class BaseReport
{
    public const ORIENTATIONS = [
        'portrait' => 'Portrait',
        'landscape' => 'Landscape',
    ];

    /**
     * Identifier unik laporan, dipakai di URL dan arsip.
     */
    abstract public static function slug(): string;

    abstract public function title(): string;

    /**
     * Komponen form Filament untuk filter laporan.
     *
     * @return array<int, Component|Field>
     */
    abstract public function filterSchema(): array;

    /**
     * Aturan validasi filter. Hanya key yang terdaftar di sini yang diteruskan ke query.
     *
     * @return array<string, mixed>
     */
    abstract public function rules(): array;

    /**
     * @param  array<string, mixed>  $filters  filter yang sudah divalidasi
     */
    abstract public function query(array $filters): Builder;

    /**
     * Definisi kolom tabel, di-key sesuai key pada row().
     *
     * @return array<string, array{label: string, width?: string, align?: string}>
     */
    abstract public function columns(): array;

    /**
     * @return array<string, string|int|null>
     */
    abstract public function row(Model $record): array;

    /**
     * Keterangan filter yang dipakai, dalam bentuk label => nilai yang mudah dibaca.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, string>
     */
    abstract public function describeFilters(array $filters): array;

    public function description(): ?string
    {
        return null;
    }

    public function defaultOrientation(): string
    {
        return 'portrait';
    }

    public function view(): string
    {
        return 'pdf.report';
    }

    /**
     * Ringkasan di bawah tabel, dikelompokkan per judul.
     *
     * @param  Collection<int, Model>  $records
     * @param  array<string, mixed>  $filters
     * @return array<string, array<string, string|int>>
     */
    public function summary(Collection $records, array $filters): array
    {
        return [];
    }

    /**
     * Tabel tambahan yang dicetak setelah tabel utama (mis. rekap per santri).
     *
     * @param  Collection<int, Model>  $records
     * @param  array<string, mixed>  $filters
     * @return array<int, array{title: string, columns: array<string, array{label: string, width?: string, align?: string}>, rows: array<int, array<string, string|int|null>>}>
     */
    public function tables(Collection $records, array $filters): array
    {
        return [];
    }

    /**
     * Nilai awal form filter di halaman Cetak Laporan.
     *
     * @return array<string, mixed>
     */
    public function defaultFilters(): array
    {
        return [];
    }

    /**
     * Validasi input filter dan buang key yang tidak terdaftar atau kosong.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    public function validateFilters(array $input): array
    {
        $validator = Validator::make($input, $this->rules(), [], $this->validationAttributes());
        $validator->after(fn (ValidatorInstance $validator) => $this->afterValidation($validator));
        $validated = $validator->validate();

        return array_filter(
            $validated,
            fn ($value) => ! in_array($value, [null, '', [], false, '0', 0], true),
        );
    }

    /**
     * @param  array<string, mixed>  $filters  filter yang sudah divalidasi
     */
    public function count(array $filters): int
    {
        return $this->query($filters)->count();
    }

    /**
     * @param  array<string, mixed>  $filters  filter yang sudah divalidasi
     */
    public function build(array $filters): ReportResult
    {
        $records = $this->query($filters)->get();

        return new ReportResult(
            report: $this,
            filters: $filters,
            filterDescriptions: $this->describeFilters($filters),
            columns: $this->columns(),
            rows: $records->map(fn (Model $record) => $this->row($record))->all(),
            summary: $this->summary($records, $filters),
            tables: $this->tables($records, $filters),
        );
    }

    /**
     * Validasi tambahan lintas field (mis. rentang tanggal). Tambahkan error lewat $validator->errors().
     */
    protected function afterValidation(ValidatorInstance $validator): void
    {
        //
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [];
    }
}
