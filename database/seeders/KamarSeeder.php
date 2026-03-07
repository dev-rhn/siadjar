<?php

namespace Database\Seeders;

use App\Models\Kamar;
use Illuminate\Database\Seeder;

class KamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kamars = [
            [
                'kd_kamar' => 'KM001',
                'nama_kamar' => 'Kamar A',
                'kapasitas' => 10,
                'keterangan' => 'Kamar untuk santri putra',
                'pegawai_id' => '2',
            ],
            [
                'kd_kamar' => 'KM002',
                'nama_kamar' => 'Kamar B',
                'kapasitas' => 20,
                'keterangan' => 'Kamar untuk santri putri',
                'pegawai_id' => '3',
            ],
            [
                'kd_kamar' => 'KM003',
                'nama_kamar' => 'Kamar C',
                'kapasitas' => 10,
                'keterangan' => 'Kamar untuk santri putra',
                'pegawai_id' => '2',
            ],
            [
                'kd_kamar' => 'KM004',
                'nama_kamar' => 'Kamar D',
                'kapasitas' => 20,
                'keterangan' => 'Kamar untuk santri putri',
                'pegawai_id' => '3',
            ],
        ];

        foreach ($kamars as $kamar) {
            Kamar::create($kamar);
        }
    }
}
