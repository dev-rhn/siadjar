<?php

namespace App\Filament\Imports;

use App\Models\DataSantri;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class DataSantriImporter extends Importer
{
    protected static ?string $model = DataSantri::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('no_kk')
                ->requiredMapping()
                ->label('No KK')
                ->rules(['required', 'max:16']),
            ImportColumn::make('nik')
                ->requiredMapping()
                ->label('NIK')
                ->rules(['required', 'max:16']),
            ImportColumn::make('nama')
                ->requiredMapping()
                ->label('Nama Lengkap')
                ->rules(['required', 'max:255']),
            ImportColumn::make('nisn')
                ->label('NISN')
                ->rules(['max:10']),
            ImportColumn::make('tmp_lhr')
                ->requiredMapping()
                ->label('Tempat Lahir')
                ->rules(['required', 'max:255']),
            ImportColumn::make('tgl_lhr')
                ->requiredMapping()
                ->label('Tanggal Lahir')
                ->rules(['required', 'date']),
            ImportColumn::make('jk')
                ->requiredMapping()
                ->label('Jenis Kelamin')
                ->rules(['required']),
            ImportColumn::make('alamat')
                ->requiredMapping()
                ->label('Alamat')
                ->rules(['required']),
            ImportColumn::make('rt')
                ->requiredMapping()
                ->label('RT')
                ->rules(['required', 'max:3']),
            ImportColumn::make('rw')
                ->requiredMapping()
                ->label('RW')
                ->rules(['required', 'max:3']),
            ImportColumn::make('kel')
                ->requiredMapping()
                ->label('Kelurahan')
                ->rules(['required', 'max:255']),
            ImportColumn::make('kec')
                ->requiredMapping()
                ->label('Kecamatan')
                ->rules(['required', 'max:255']),
            ImportColumn::make('kab')
                ->requiredMapping()
                ->label('Kabupaten')
                ->rules(['required', 'max:255']),
            ImportColumn::make('prov')
                ->requiredMapping()
                ->label('Provinsi')
                ->rules(['required', 'max:255']),
            ImportColumn::make('kode_pos')
                ->requiredMapping()
                ->label('Kode Pos')
                ->rules(['required', 'max:5']),
            ImportColumn::make('asal_sekolah')
                ->requiredMapping()
                ->label('Asal Sekolah')
                ->rules(['required', 'max:255']),
            ImportColumn::make('keterangan')
                ->requiredMapping()
                ->label('Keterangan')
                ->rules(['required']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->label('Status')
                ->rules(['required']),
            ImportColumn::make('tahun_masuk')
                ->requiredMapping()
                ->label('Tahun Masuk')
                ->rules(['required']),
            ImportColumn::make('jenjang')
                ->requiredMapping()
                ->label('Jenjang')
                ->rules(['required']),
            ImportColumn::make('nik_ayah')
                ->requiredMapping()
                ->label('NIK Ayah')
                ->rules(['max:16']),
            ImportColumn::make('nama_ayah')
                ->requiredMapping()
                ->label('Nama Ayah')
                ->rules(['max:255']),
            ImportColumn::make('tmp_lhr_ayah')
                ->requiredMapping()
                ->label('Tempat Lahir Ayah')
                ->rules(['max:255']),
            ImportColumn::make('tgl_lhr_ayah')
                ->requiredMapping()
                ->label('Tanggal Lahir Ayah')
                ->rules(['date']),
            ImportColumn::make('pendidikan_ayah')
                ->requiredMapping()
                ->label('Pendidikan Ayah')
                ->rules(['max:255']),
            ImportColumn::make('pekerjaan_ayah')
                ->requiredMapping()
                ->label('Pekerjaan Ayah')
                ->rules(['max:255']),
            ImportColumn::make('nik_ibu')
                ->requiredMapping()
                ->label('NIK Ibu')
                ->rules(['max:16']),
            ImportColumn::make('nama_ibu')
                ->requiredMapping()
                ->label('Nama Ibu')
                ->rules(['max:255']),
            ImportColumn::make('tmp_lhr_ibu')
                ->requiredMapping()
                ->label('Tempat Lahir Ibu')
                ->rules(['max:255']),
            ImportColumn::make('tgl_lhr_ibu')
                ->requiredMapping()
                ->label('Tanggal Lahir Ibu')
                ->rules(['date']),
            ImportColumn::make('pendidikan_ibu')
                ->requiredMapping()
                ->label('Pendidikan Ibu')
                ->rules(['max:255']),
            ImportColumn::make('pekerjaan_ibu')
                ->requiredMapping()
                ->label('Pekerjaan Ibu')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): DataSantri
    {
        return DataSantri::firstOrNew([
            'id' => $this->data['id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your data santri import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
