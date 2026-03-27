<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'name' => 'Admin Puskesmas',
            'email' => 'admin@puskesmas.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // DOKTER
        User::create([
            'name' => 'Dr. Andi',
            'email' => 'dokter@puskesmas.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter'
        ]);
    }
}
