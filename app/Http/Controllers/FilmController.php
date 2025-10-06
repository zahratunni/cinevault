<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use Illuminate\Support\Str;

class FilmController extends Controller
{
    public function show($id)
    {
        $today = now()->format('Y-m-d');
        
        $film = Film::with(['jadwals' => function($query) use ($today) {
            $query->where('status_jadwal', 'Active')
                  ->where('tanggal_tayang', $today)
                  ->orderBy('jam_mulai', 'asc');
        }, 'jadwals.studio'])
        ->findOrFail($id);
        
        $jadwalHariIni = $film->jadwals;
        
        return view('films.show', compact('film', 'jadwalHariIni'));
    }

    public function playingNow()
    {
    $films = Film::where('status_tayang', 'Playing Now')->get();

    return view('films.playing-now', compact('films'));
    }

    public function upcoming()
{
    $films = Film::where('status_tayang', 'Upcoming')->get();

    return view('films.upcoming', compact('films'));
}


}