<?php

namespace Database\Seeders;

use App\Models\KategoriPelanggaran;
use Illuminate\Database\Seeder;

class KategoriPelanggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriPelanggarans = [
            [
                'nama_kategori' => 'Ringan',
                'keterangan' => 'Pelanggaran yang tidak terlalu serius, seperti terlambat masuk kelas atau tidak memakai seragam lengkap.',
            ],
            [
                'nama_kategori' => 'Sedang',
                'keterangan' => 'Pelanggaran yang lebih serius, seperti merokok di lingkungan sekolah atau membawa barang terlarang.',
            ],
            [
                'nama_kategori' => 'Berat',
                'keterangan' => 'Pelanggaran yang sangat serius, seperti perkelahian, vandalisme, atau tindakan kriminal lainnya.',
            ],
        ];

        foreach ($kategoriPelanggarans as $kategori) {
            KategoriPelanggaran::create($kategori);
        }
    }
}
