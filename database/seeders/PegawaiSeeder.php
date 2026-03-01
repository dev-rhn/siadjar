<?php

namespace Database\Seeders;

use App\Models\Kepegawaian;

use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pegawai = [
            [
                'user_id' => 2,
                'kd_pegawai' => 'PG001',
                'nama_pegawai' => 'Nura',
                'jabatan_id' => 5,
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'no_telepon' => '081234567890',
                'status_kepegawaian' => 'Tetap',
            ],
            [
                'user_id' => 4,
                'kd_pegawai' => 'PG002',
                'nama_pegawai' => 'Enzo',
                'jabatan_id' => 7,
                'alamat' => 'Jl. Sudirman No. 20, Bandung',
                'no_telepon' => '081234567891',
                'status_kepegawaian' => 'Tetap',
            ],
            [
                'user_id' => 3,
                'kd_pegawai' => 'PG003',
                'nama_pegawai' => 'Hasya',
                'jabatan_id' => 4,
                'alamat' => 'Jl. Gatot Subroto No. 30, Surabaya',
                'no_telepon' => '081234567892',
                'status_kepegawaian' => 'Tetap',
            ],
        ];

        foreach ($pegawai as $key => $var) {
            Kepegawaian::create($var);
        }
    }
}
