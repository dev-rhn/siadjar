<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            [
                'kd_jabatan' => 'PD001',
                'nama_jabatan' => 'Guru',
                'keterangan' => 'Mengajar mata pelajaran',
            ],
            [
                'kd_jabatan' => 'PD002',
                'nama_jabatan' => 'Tata Usaha',
                'keterangan' => 'Mengelola administrasi sekolah',
            ],
            [
                'kd_jabatan' => 'PD003',
                'nama_jabatan' => 'Kepala Sekolah',
                'keterangan' => 'Memimpin dan mengelola sekolah',
            ],
            [
                'kd_jabatan' => 'Y001',
                'nama_jabatan' => 'Pimpinan Yayasan',
                'keterangan' => 'Memimpin dan mengelola yayasan',
            ],
            [
                'kd_jabatan' => 'Y002',
                'nama_jabatan' => 'Kesekretariatan Yayasan',
                'keterangan' => 'Mengelola sekretariat yayasan',
            ],
            [
                'kd_jabatan' => 'Y003',
                'nama_jabatan' => 'Tim IT Yayasan',
                'keterangan' => 'Mengelola sistem informasi yayasan',
            ],
            [
                'kd_jabatan' => 'Y004',
                'nama_jabatan' => 'Tim External Yayasan',
                'keterangan' => 'Mengelola hubungan dengan pihak eksternal yayasan',
            ],
        ];

        foreach ($jabatans as $jabatan) {
            \App\Models\Jabatan::create($jabatan);
        }
    }
}
