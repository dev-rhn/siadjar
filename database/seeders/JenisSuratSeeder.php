<?php

namespace Database\Seeders;

use App\Models\JenisSurat;

use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurats = [
            [
                'nama_jenis' => 'Surat Izin',
                'kode_angka' => '001',
                'keterangan' => 'Surat yang digunakan untuk meminta izin, seperti izin tidak masuk sekolah atau izin terlambat.',
            ],
            [
                'nama_jenis' => 'Surat Peringatan',
                'kode_angka' => '002',
                'keterangan' => 'Surat yang digunakan untuk memberikan peringatan kepada siswa yang melanggar aturan sekolah.',
            ],
            [
                'nama_jenis' => 'Surat Keterangan',
                'kode_angka' => '003',
                'keterangan' => 'Surat yang digunakan untuk memberikan keterangan tertentu, seperti keterangan sakit atau keterangan lulus.',
            ],
            [
                'nama_jenis' => 'Surat Undangan',
                'kode_angka' => '004',
                'keterangan' => 'Surat yang digunakan untuk mengundang siswa atau orang tua untuk menghadiri suatu acara atau pertemuan di sekolah.',
            ],
            [
                'nama_jenis' => 'Surat Pemberitahuan',
                'kode_angka' => '005',
                'keterangan' => 'Surat yang digunakan untuk memberitahukan informasi penting kepada siswa atau orang tua, seperti jadwal ujian atau perubahan aturan sekolah.',
            ],
        ];

        foreach ($jenisSurats as $key => $var) {
            JenisSurat::create($var);
        }
    }
}
