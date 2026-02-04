<?php

namespace Database\Seeders;

use App\Models\DataSantri;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class SantriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan locale Indonesia

        for ($i = 1; $i <= 50; $i++) {
            $no_kk = $faker->numerify('3201############');
            $jenjang = $faker->randomElement(['SD', 'SMP', 'SMA']);
            
            // Logika umur berdasarkan jenjang
            $tahunMasuk = 2026 - $faker->numberBetween(0, 3);
            $umur = match($jenjang) {
                'SD' => $faker->numberBetween(7, 12),
                'SMP' => $faker->numberBetween(13, 15),
                'SMA' => $faker->numberBetween(16, 18),
            };

            DataSantri::create([
                'no_kk' => $no_kk,
                'nik' => $faker->numerify('3201############'),
                'nama' => $faker->name(),
                'nisn' => $faker->numerify('00########'),
                'tmp_lhr' => $faker->city(),
                'tgl_lhr' => $faker->dateTimeBetween("-" . ($umur+1) . " years", "-$umur years")->format('Y-m-d'),
                'jk' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                
                // Alamat
                'alamat' => $faker->address(),
                'rt' => $faker->numerify('0##'),
                'rw' => $faker->numerify('0##'),
                'kel' => $faker->city(), 
                'kec' => $faker->city(),
                'kab' => $faker->city(),
                'prov' => $faker->state(),
                'kode_pos' => $faker->postcode(),
                
                // Akademik
                'asal_sekolah' => 'Sekolah ' . $faker->company(),
                'keterangan' => $faker->randomElement(['Dhuafa', 'Yatim', 'Piatu', 'Yatim Piatu', 'Reguler']),
                'status' => 'Aktif',
                'tahun_masuk' => $tahunMasuk,
                'jenjang' => $jenjang,

                // Data Ayah
                'nik_ayah' => $faker->numerify('3201############'),
                'nama_ayah' => $faker->name('male'),
                'tmp_lhr_ayah' => $faker->city(),
                'tgl_lhr_ayah' => $faker->date('Y-m-d', '-40 years'),
                'pendidikan_ayah' => $faker->randomElement(['SMA', 'D3', 'S1', 'S2']),
                'pekerjaan_ayah' => $faker->jobTitle(),

                // Data Ibu
                'nik_ibu' => $faker->numerify('3201############'),
                'nama_ibu' => $faker->name('female'),
                'tmp_lhr_ibu' => $faker->city(),
                'tgl_lhr_ibu' => $faker->date('Y-m-d', '-35 years'),
                'pendidikan_ibu' => $faker->randomElement(['SMA', 'D3', 'S1']),
                'pekerjaan_ibu' => $faker->jobTitle(),

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
