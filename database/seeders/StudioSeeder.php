<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio;
use App\Models\User;

class StudioSeeder extends Seeder
{
    public function run()
    {
        // Ambil user admin pertama (atau buat dummy jika belum ada)
        $admin = User::first() ?? User::create([
            'name' => 'Admin CINEVAULT',
            'email' => 'admin@cinevault.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $studios = [
            [
                'nama_studio' => 'Studio 1',
                'kapasitas' => 50,
                'created_by' => $admin->user_id
            ],
            [
                'nama_studio' => 'Studio 2',
                'kapasitas' => 75,
                'created_by' => $admin->user_id
            ],
            [
                'nama_studio' => 'Studio 3',
                'kapasitas' => 100,
                'created_by' => $admin->user_id
            ],
        ];

        foreach ($studios as $studio) {
            Studio::create($studio);
        }
    }
}