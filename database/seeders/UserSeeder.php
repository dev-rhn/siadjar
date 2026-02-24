<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('123'),
            ],
        ];

        foreach($user as $key => $var){
            User::create($var);
        }
    }
}
