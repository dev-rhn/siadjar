<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SantriSeeder::class,
            UserSeeder::class,
            JabatanSeeder::class,
            KelasSeeder::class,
            KamarSeeder::class,
            KategoriPelanggaranSeeder::class,
            JenisSuratSeeder::class,
            PegawaiSeeder::class,
        ]);
    }
}
