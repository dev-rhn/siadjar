<?php

namespace App\Reports;

use App\Models\DataSantri;
use App\Models\Kamar;
use App\Models\Kelas;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SantriReport extends BaseReport
{
    private const STATUS = ['Aktif', 'Tidak Aktif'];

    private const JENJANG = ['SD', 'SMP', 'SMA'];

    private const JENIS_KELAMIN = ['Laki-laki', 'Perempuan'];

    public static function slug(): string
    {
        return 'santri';
    }

    public function title(): string
    {
        return 'Rekap Data Santri';
    }

    public function description(): ?string
    {
        return 'Daftar santri beserta kelas, kamar, dan status.';
    }

    public function defaultOrientation(): string
    {
        return 'landscape';
    }

    public function filterSchema(): array
    {
        return [
            Select::make('status')
                ->label('Status')
                ->options(array_combine(self::STATUS, self::STATUS))
                ->placeholder('Semua status'),
            Select::make('jenjang')
                ->label('Jenjang')
                ->options(array_combine(self::JENJANG, self::JENJANG))
                ->placeholder('Semua jenjang'),
            Select::make('jk')
                ->label('Jenis Kelamin')
                ->options(array_combine(self::JENIS_KELAMIN, self::JENIS_KELAMIN))
                ->placeholder('Semua'),
            Select::make('kelas_id')
                ->label('Kelas')
                ->options(fn () => Kelas::query()->orderBy('nama_kelas')->pluck('nama_kelas', 'id'))
                ->searchable()
                ->placeholder('Semua kelas'),
            Select::make('kamar_id')
                ->label('Kamar')
                ->options(fn () => Kamar::query()->orderBy('nama_kamar')->pluck('nama_kamar', 'id'))
                ->searchable()
                ->placeholder('Semua kamar'),
            Select::make('tahun_masuk')
                ->label('Tahun Masuk')
                ->options(fn () => $this->tahunMasukOptions())
                ->placeholder('Semua tahun'),
        ];
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(self::STATUS)],
            'jenjang' => ['nullable', Rule::in(self::JENJANG)],
            'jk' => ['nullable', Rule::in(self::JENIS_KELAMIN)],
            'kelas_id' => ['nullable', 'integer', Rule::exists('kelas', 'id')],
            'kamar_id' => ['nullable', 'integer', Rule::exists('kamars', 'id')],
            'tahun_masuk' => ['nullable', 'string', 'max:10'],
        ];
    }

    public function query(array $filters): Builder
    {
        return DataSantri::query()
            ->with(['kelas', 'kamar'])
            ->when($filters['status'] ?? null, fn (Builder $query, $value) => $query->where('status', $value))
            ->when($filters['jenjang'] ?? null, fn (Builder $query, $value) => $query->where('jenjang', $value))
            ->when($filters['jk'] ?? null, fn (Builder $query, $value) => $query->where('jk', $value))
            ->when($filters['kelas_id'] ?? null, fn (Builder $query, $value) => $query->where('kelas_id', $value))
            ->when($filters['kamar_id'] ?? null, fn (Builder $query, $value) => $query->where('kamar_id', $value))
            ->when($filters['tahun_masuk'] ?? null, fn (Builder $query, $value) => $query->where('tahun_masuk', $value))
            ->orderBy('nama');
    }

    public function columns(): array
    {
        return [
            'nisn' => ['label' => 'NISN', 'width' => '11%'],
            'nama' => ['label' => 'Nama', 'width' => '20%', 'link' => true],
            'jk' => ['label' => 'JK', 'width' => '5%', 'align' => 'center'],
            'ttl' => ['label' => 'Tempat, Tgl Lahir', 'width' => '18%'],
            'jenjang' => ['label' => 'Jenjang', 'width' => '7%', 'align' => 'center'],
            'kelas' => ['label' => 'Kelas', 'width' => '10%'],
            'kamar' => ['label' => 'Kamar', 'width' => '10%'],
            'tahun_masuk' => ['label' => 'Thn Masuk', 'width' => '8%', 'align' => 'center'],
            'status' => ['label' => 'Status', 'width' => '8%', 'align' => 'center'],
        ];
    }

    /**
     * @param  DataSantri  $record
     */
    public function row(Model $record): array
    {
        $tanggalLahir = $record->tgl_lhr
            ? Carbon::parse($record->tgl_lhr)->locale('id')->translatedFormat('d F Y')
            : null;

        return [
            'nisn' => $record->nisn,
            'nama' => $record->nama,
            '_santri_id' => $record->id,
            'jk' => match ($record->jk) {
                'Laki-laki' => 'L',
                'Perempuan' => 'P',
                default => null,
            },
            'ttl' => collect([$record->tmp_lhr, $tanggalLahir])->filter()->implode(', '),
            'jenjang' => $record->jenjang,
            'kelas' => $record->kelas?->nama_kelas,
            'kamar' => $record->kamar?->nama_kamar,
            'tahun_masuk' => $record->tahun_masuk,
            'status' => $record->status,
        ];
    }

    public function summary(Collection $records, array $filters): array
    {
        return [
            'Jenis Kelamin' => [
                'Laki-laki' => $records->where('jk', 'Laki-laki')->count(),
                'Perempuan' => $records->where('jk', 'Perempuan')->count(),
                'Total' => $records->count(),
            ],
            'Status' => [
                'Aktif' => $records->where('status', 'Aktif')->count(),
                'Tidak Aktif' => $records->where('status', 'Tidak Aktif')->count(),
            ],
        ];
    }

    public function describeFilters(array $filters): array
    {
        return array_filter([
            'Status' => $filters['status'] ?? null,
            'Jenjang' => $filters['jenjang'] ?? null,
            'Jenis Kelamin' => $filters['jk'] ?? null,
            'Kelas' => isset($filters['kelas_id']) ? Kelas::find($filters['kelas_id'])?->nama_kelas : null,
            'Kamar' => isset($filters['kamar_id']) ? Kamar::find($filters['kamar_id'])?->nama_kamar : null,
            'Tahun Masuk' => $filters['tahun_masuk'] ?? null,
        ]);
    }

    protected function validationAttributes(): array
    {
        return [
            'jk' => 'jenis kelamin',
            'kelas_id' => 'kelas',
            'kamar_id' => 'kamar',
            'tahun_masuk' => 'tahun masuk',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function tahunMasukOptions(): array
    {
        return DataSantri::query()
            ->whereNotNull('tahun_masuk')
            ->where('tahun_masuk', '!=', '')
            ->distinct()
            ->orderByDesc('tahun_masuk')
            ->pluck('tahun_masuk', 'tahun_masuk')
            ->all();
    }
}
