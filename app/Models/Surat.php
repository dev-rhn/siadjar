<?php

namespace App\Models;

use App\Models\JenisSurat;
use App\Models\Kepegawaian;
use App\Models\PenerimaSurat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surats';

    protected $fillable = [
        'jenis_surat_id',
        'pegawai_id',
        'arah',
        'nomor_surat',
        'tanggal',
        'perihal',
        'keterangan',
        'lampiran',
        'status',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Kepegawaian::class, 'pegawai_id');
    }

    public function penerimaSurats()
    {
        return $this->hasMany(PenerimaSurat::class, 'surat_id');
    }

    public static function generateNomor(string $arah, int $jenisSuratId, ?string $tanggal = null): string
    {
        $tanggal = $tanggal ? Carbon::parse($tanggal) : Carbon::now();
        $bulan = $tanggal->month;
        $tahun = $tanggal->year;

        $kodeArah = $arah === 'Masuk' ? 'M' : 'K';

        $jenis = JenisSurat::find($jenisSuratId);
        $kodeAngka = $jenis->kode_angka;

        // Hitung urutan: per arah + jenis + bulan + tahun
        $urutan = self::where('arah', $arah)
            ->where('jenis_surat_id', $jenisSuratId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count() + 1;

        $bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][$bulan - 1];

        return sprintf('%s-%03d/%s/YADR/%s/%d', $kodeArah, $urutan, $kodeAngka, $bulanRomawi, $tahun);
    }
}
