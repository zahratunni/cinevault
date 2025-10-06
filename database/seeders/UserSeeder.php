<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       // 1. Akun OWNER (Tingkat Akses Tertinggi)
        User::create([
            'user_id' => 1, 
            'username' => 'owner_utama',
            'email' => 'owner@cinevault.com',
            'password' => Hash::make('password123'), // Login: owner@cinevault.com / password123
            'nama_lengkap' => 'Pemilik Bioskop',
            'no_telepon' => '08100000000',
            'role' => 'Owner', // <-- Peran Owner
        ]);

        // 2. Akun ADMIN (Mengelola Operasional CRUD)
        User::create([
            'user_id' => 2,
            'username' => 'admin_operasional',
            'email' => 'admin@cinevault.com',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Administrator Operasional',
            'no_telepon' => '08111111111',
            'role' => 'Admin', // <-- Peran Admin
        ]);

        // 3. Akun KASIR (Untuk Verifikasi Transaksi)
        User::create([
            'user_id' => 3,
            'username' => 'kasir_a',
            'email' => 'kasir@cinevault.com',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Kasir Loket A',
            'no_telepon' => '08222222222',
            'role' => 'Kasir', // <-- Peran Kasir
        ]);
        
        // 4. Akun CUSTOMER (Pengguna Aplikasi)
        User::create([
            'user_id' => 4,
            'username' => 'customer_jeje',
            'email' => 'jeje@gmail.com',
            'password' => Hash::make('password123'), 
            'nama_lengkap' => 'Jeje Customer',
            'no_telepon' => '08333333333',
            'role' => 'Customer', // <-- Peran Customer
        ]);
    }
}
