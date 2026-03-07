<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            [
                'kd_kelas' => 'SMP001',
                'nama_kelas' => 'Kelas 1',
                'tingkat' => 'SMP',
                'pegawai_id' => '2',
            ],
            [
                'kd_kelas' => 'SMP002',
                'nama_kelas' => 'Kelas 2',
                'tingkat' => 'SMP',
                'pegawai_id' => '3',
            ],
            [
                'kd_kelas' => 'SMP003',
                'nama_kelas' => 'Kelas 3',
                'tingkat' => 'SMP',
                'pegawai_id' => '3',
            ],
            [
                'kd_kelas' => 'SMA001',
                'nama_kelas' => 'Kelas 4',
                'tingkat' => 'SMA',
                'pegawai_id' => '2',
            ],
            [
                'kd_kelas' => 'SMA002',
                'nama_kelas' => 'Kelas 5',
                'tingkat' => 'SMA',
                'pegawai_id' => '3',
            ],
            [
                'kd_kelas' => 'SMA003',
                'nama_kelas' => 'Kelas 6',
                'tingkat' => 'SMA',
                'pegawai_id' => '2',
            ],
        ];

        foreach ($kelas as $k) {
            \App\Models\Kelas::create($k);
        }
    }
}
