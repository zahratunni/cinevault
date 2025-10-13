<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jadwal;
use App\Models\Film;
use App\Models\Studio;
use App\Models\User;
use Carbon\Carbon;

class GenerateJadwalAdvance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jadwal:generate-advance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate jadwal advance otomatis untuk film Playing Now dan studio yang tersedia';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $admin = User::first(); // asumsikan user pertama adalah admin
        $films = Film::where('status_tayang', 'Playing Now')->get();
        $studios = Studio::all();

        if ($films->isEmpty() || $studios->isEmpty()) {
            $this->warn('Tidak ada film Playing Now atau studio. Proses generate jadwal dibatalkan.');
            return 0;
        }

        // Generate jadwal untuk 7 hari ke depan (hari ini + 6 hari)
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->addDays($i);
            $isWeekend = $date->isWeekend();
            $hargaReguler = $isWeekend ? 50000 : 35000;

            foreach ($films as $film) {
                // jam tayang yang sama untuk setiap hari
                $jamTayang = [
                    ['11:00', '13:00'],
                    ['14:00', '16:00'],
                    ['16:30', '18:30'],
                    ['19:00', '21:00'],
                ];

                $studios->shuffle();
                $jumlahJadwal = rand(3, 4);
                $jadwalDipilih = array_rand($jamTayang, $jumlahJadwal);

                if (!is_array($jadwalDipilih)) {
                    $jadwalDipilih = [$jadwalDipilih];
                }

                foreach ($jadwalDipilih as $index) {
                    $jam = $jamTayang[$index];
                    $studio = $studios->random();

                    // Cek dulu kalau jadwal ini sudah ada supaya gak duplicate
                    $exists = Jadwal::where('film_id', $film->film_id)
                        ->where('studio_id', $studio->studio_id)
                        ->where('tanggal_tayang', $date->format('Y-m-d'))
                        ->where('jam_mulai', $jam[0])
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    Jadwal::create([
                        'film_id' => $film->film_id,
                        'studio_id' => $studio->studio_id,
                        'tanggal_tayang' => $date->format('Y-m-d'),
                        'jam_mulai' => $jam[0],
                        'jam_selesai' => $jam[1],
                        'harga_reguler' => $hargaReguler,
                        'status_jadwal' => 'Active',
                        'created_by' => $admin->user_id
                    ]);
                }
            }
        }

        $this->info('Generate jadwal advance berhasil untuk 7 hari ke depan.');

        return 0;
    }
}
