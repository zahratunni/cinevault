<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Film;
use App\Models\Studio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Jadwal::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::first();
        if (!$admin) {
            $this->command->warn('❌ Tidak ada user! Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $films = Film::where('status_tayang', 'Playing Now')->get();
        $studios = Studio::all();

        if ($films->isEmpty()) {
            $this->command->warn('❌ Tidak ada film dengan status "Playing Now"!');
            return;
        }

        if ($studios->isEmpty()) {
            $this->command->warn('❌ Tidak ada data studio!');
            return;
        }

        $jamTayang = [
            ['mulai' => '11:00:00', 'selesai' => '13:30:00'],
            ['mulai' => '14:00:00', 'selesai' => '16:30:00'],
            ['mulai' => '17:00:00', 'selesai' => '19:30:00'],
            ['mulai' => '20:00:00', 'selesai' => '22:30:00'],
        ];

        $this->command->info('🎬 Membuat jadwal tayang untuk semua film Now Playing...');

        // Loop dua hari: hari ini & besok
        for ($day = 0; $day < 2; $day++) {
            $tanggal = Carbon::today()->addDays($day);
            $isWeekend = $tanggal->isWeekend();
            $hargaReguler = $isWeekend ? 50000 : 35000;

            $this->command->info("📅 Tanggal: {$tanggal->format('Y-m-d')}");

            // Simpan kombinasi yang sudah dipakai (supaya tidak bentrok)
            $slotTerpakai = [];

            foreach ($films as $film) {
                // Ambil 1–3 jadwal acak per film
                $jumlahTayang = rand(1, 3);
                $jamTayangDipilih = collect($jamTayang)->shuffle()->take($jumlahTayang);
                $jadwalTerbuat = 0;

                foreach ($jamTayangDipilih as $jam) {
                    // Cari studio yang belum terpakai di jam ini
                    $studioTersedia = $studios->first(function ($studio) use ($slotTerpakai, $tanggal, $jam) {
                        $key = "{$studio->studio_id}-{$tanggal->format('Y-m-d')}-{$jam['mulai']}";
                        return !in_array($key, $slotTerpakai);
                    });

                    if (!$studioTersedia) continue;

                    $slotTerpakai[] = "{$studioTersedia->studio_id}-{$tanggal->format('Y-m-d')}-{$jam['mulai']}";

                    Jadwal::create([
                        'film_id' => $film->film_id,
                        'studio_id' => $studioTersedia->studio_id,
                        'created_by' => $admin->user_id,
                        'tanggal_tayang' => $tanggal->format('Y-m-d'),
                        'jam_mulai' => $jam['mulai'],
                        'jam_selesai' => $jam['selesai'],
                        'harga_reguler' => $hargaReguler,
                        'status_jadwal' => 'Active',
                    ]);

                    $jadwalTerbuat++;
                }

                // ✅ Pastikan tiap film minimal punya 1 jadwal hari ini DAN besok
                if ($jadwalTerbuat === 0) {
                    $jam = collect($jamTayang)->random();
                    $studioTersedia = $studios->first(function ($studio) use ($slotTerpakai, $tanggal, $jam) {
                        $key = "{$studio->studio_id}-{$tanggal->format('Y-m-d')}-{$jam['mulai']}";
                        return !in_array($key, $slotTerpakai);
                    });

                    if ($studioTersedia) {
                        $slotTerpakai[] = "{$studioTersedia->studio_id}-{$tanggal->format('Y-m-d')}-{$jam['mulai']}";

                        Jadwal::create([
                            'film_id' => $film->film_id,
                            'studio_id' => $studioTersedia->studio_id,
                            'created_by' => $admin->user_id,
                            'tanggal_tayang' => $tanggal->format('Y-m-d'),
                            'jam_mulai' => $jam['mulai'],
                            'jam_selesai' => $jam['selesai'],
                            'harga_reguler' => $hargaReguler,
                            'status_jadwal' => 'Active',
                        ]);
                        $this->command->info("  🔁 {$film->judul} — ditambahkan 1 jadwal (agar tidak kosong di {$tanggal->format('Y-m-d')})");
                    }
                }

                $this->command->info("  ✅ {$film->judul} ({$jadwalTerbuat} jadwal)");
            }
        }

        $this->command->info('');
        $this->command->info('✨ Total jadwal berhasil dibuat: ' . Jadwal::count());
    }
}
