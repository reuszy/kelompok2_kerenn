<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'reuszy'],
            [
                'password'     => Hash::make('password'),
                'phone_number' => '0877xxxxxxxx',
                'address'      => 'Alamat Admin',
                'role'         => 'admin',
                'verified_at'  => now(),
            ]
        );

        User::updateOrCreate(
            ['username' => 'petugas'],
            [
                'password'     => Hash::make('password'),
                'phone_number' => '08778xxxxxxx',
                'address'      => 'Alamat Petugas',
                'role'         => 'petugas',
                'verified_at'  => now(),
            ]
        );

        // Peminjam
        User::updateOrCreate(
            ['username' => 'mahasiswa'],
            [
                'password'     => Hash::make('password'),
                'phone_number' => '087xxxxxxxxx',
                'address'      => 'Alamat Peminjam',
                'role'         => 'peminjam',
                'verified_at'  => now(),
            ]
        );
    }
}
