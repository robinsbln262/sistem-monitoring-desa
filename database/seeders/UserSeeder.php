<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'nama_user' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'level' => 'admin',
            'bidang_keahlian' => null,
            'no_telp' => null,
            'alamat' => null,
        ]);

        // KEPALA DESA
        User::create([
            'nama_user' => 'Kepala Desa Kutabuluh',
            'username' => 'kepala_desa',
            'password' => Hash::make('kades123'),
            'level' => 'kepala_desa',
            'bidang_keahlian' => null,
            'no_telp' => null,
            'alamat' => null,
        ]);

        // REKANAN 1 - Konstruksi
        User::create([
            'nama_user' => 'Rekanan 1 - Konstruksi',
            'username' => 'rekanan1',
            'password' => Hash::make('rekanan123'),
            'level' => 'rekanan',
            'bidang_keahlian' => 'Konstruksi Bangunan',
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Contoh No. 1, Kutabuluh',
        ]);

        // REKANAN 2 - Jalan
        User::create([
            'nama_user' => 'Rekanan 2 - Jalan',
            'username' => 'rekanan2',
            'password' => Hash::make('rekanan123'),
            'level' => 'rekanan',
            'bidang_keahlian' => 'Pembangunan Jalan',
            'no_telp' => '081234567891',
            'alamat' => 'Jl. Contoh No. 2, Kutabuluh',
        ]);

        // REKANAN 3 - Listrik
        User::create([
            'nama_user' => 'Rekanan 3 - Listrik',
            'username' => 'rekanan3',
            'password' => Hash::make('rekanan123'),
            'level' => 'rekanan',
            'bidang_keahlian' => 'Instalasi Listrik',
            'no_telp' => '081234567892',
            'alamat' => 'Jl. Contoh No. 3, Kutabuluh',
        ]);
    }
}
