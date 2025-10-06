<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Film;
use App\Models\Studio;
use App\Models\User;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        $admin = User::first();
        $films = Film::where('status_tayang', 'Playing Now')->get();
        $studios = Studio::all();

        if ($films->isEmpty() || $studios->isEmpty()) {
            $this->command->warn('Tidak ada film Playing Now atau studio. Seeder jadwal dilewati.');
            return;
        }

        // Hanya generate untuk HARI INI saja
        $today = Carbon::now();
        
        // Tentukan harga berdasarkan weekday/weekend HARI INI
        $isWeekend = $today->isWeekend();
        $hargaReguler = $isWeekend ? 50000 : 35000;
        
        foreach ($films as $film) {
            // Setiap film dapat 3-4 jadwal di hari ini
            $studios->shuffle();
            
            $jamTayang = [
                ['11:00', '13:00'],
                ['14:00', '16:00'],
                ['16:30', '18:30'],
                ['19:00', '21:00'],
            ];
            
            $jumlahJadwal = rand(3, 4);
            $jadwalDipilih = array_rand($jamTayang, $jumlahJadwal);
            
            if (!is_array($jadwalDipilih)) {
                $jadwalDipilih = [$jadwalDipilih];
            }
            
            foreach ($jadwalDipilih as $index) {
                $jam = $jamTayang[$index];
                $studio = $studios->random();
                
                try {
                    Jadwal::create([
                        'film_id' => $film->film_id,
                        'studio_id' => $studio->studio_id,
                        'tanggal_tayang' => $today->format('Y-m-d'),
                        'jam_mulai' => $jam[0],
                        'jam_selesai' => $jam[1],
                        'harga_reguler' => $hargaReguler,
                        'status_jadwal' => 'Active',
                        'created_by' => $admin->user_id
                    ]);
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
    }
}