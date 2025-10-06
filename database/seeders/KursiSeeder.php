<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio;
use App\Models\Kursi;

class KursiSeeder extends Seeder
{
    public function run()
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            $kapasitas = $studio->kapasitas;
            
            // Tentukan layout berdasarkan kapasitas
            if ($kapasitas == 50) {
                $rows = ['A', 'B', 'C', 'D', 'E'];
                $seatsPerRow = 10;
            } elseif ($kapasitas == 75) {
                $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
                $seatsPerRow = 11;
            } else { // 100
                $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                $seatsPerRow = 10;
            }

            // Generate kursi untuk setiap baris
            foreach ($rows as $row) {
                for ($seat = 1; $seat <= $seatsPerRow; $seat++) {
                    Kursi::create([
                        'studio_id' => $studio->studio_id,
                        'kode_kursi' => $row . $seat,
                        'baris' => $row,
                        'nomor_kursi' => $seat
                    ]);
                }
            }
        }
    }
}