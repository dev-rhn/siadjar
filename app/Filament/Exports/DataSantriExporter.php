<?php

namespace App\Filament\Exports;

use App\Models\DataSantri;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class DataSantriExporter extends Exporter
{
    protected static ?string $model = DataSantri::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('no_kk')
                ->label('No KK'),
            ExportColumn::make('nik')
                ->label('NIK'),
            ExportColumn::make('nama')
                ->label('Nama Lengkap'),
            ExportColumn::make('nisn')
                ->label('NISN'),
            ExportColumn::make('tmp_lhr')
                ->label('Tempat Lahir'),
            ExportColumn::make('tgl_lhr')
                ->label('Tanggal Lahir'),
            ExportColumn::make('jk')
                ->label('Jenis Kelamin'),
            ExportColumn::make('alamat')
                ->label('Alamat'),
            ExportColumn::make('rt')
                ->label('RT'),
            ExportColumn::make('rw')
                ->label('RW'),
            ExportColumn::make('kel')
                ->label('Kelurahan'),
            ExportColumn::make('kec')
                ->label('Kecamatan'),
            ExportColumn::make('kab')
                ->label('Kabupaten'),
            ExportColumn::make('prov')
                ->label('Provinsi'),
            ExportColumn::make('kode_pos')
                ->label('Kode Pos'),
            ExportColumn::make('asal_sekolah')
                ->label('Asal Sekolah'),
            ExportColumn::make('keterangan')
                ->label('Keterangan'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('tahun_masuk')
                ->label('Tahun Masuk'),
            ExportColumn::make('jenjang')
                ->label('Jenjang'),
            ExportColumn::make('nik_ayah')
                ->label('NIK Ayah'),
            ExportColumn::make('nama_ayah')
                ->label('Nama Ayah'),
            ExportColumn::make('tmp_lhr_ayah')
                ->label('Tempat Lahir Ayah'),
            ExportColumn::make('tgl_lhr_ayah')
                ->label('Tanggal Lahir Ayah'),
            ExportColumn::make('pendidikan_ayah')
                ->label('Pendidikan Ayah'),
            ExportColumn::make('pekerjaan_ayah')
                ->label('Pekerjaan Ayah'),
            ExportColumn::make('nik_ibu')
                ->label('NIK Ibu'),
            ExportColumn::make('nama_ibu')
                ->label('Nama Ibu'),
            ExportColumn::make('tmp_lhr_ibu')
                ->label('Tempat Lahir Ibu'),
            ExportColumn::make('tgl_lhr_ibu')
                ->label('Tanggal Lahir Ibu'),
            ExportColumn::make('pendidikan_ibu')
                ->label('Pendidikan Ibu'),
            ExportColumn::make('pekerjaan_ibu')
                ->label('Pekerjaan Ibu'),
            ExportColumn::make('created_at')
                ->label('Dibuat Pada'),
            ExportColumn::make('updated_at')
                ->label('Diperbarui Pada'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your data santri export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
