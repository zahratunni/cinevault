<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;

class HomeController extends Controller
{
    public function index()
    {
        $playingNow = Film::where('status_tayang', 'Playing Now')
        ->orderBy('created_at', 'desc')
        ->limit(7)
        ->get();

        $upcoming = Film::where('status_tayang', 'Upcoming')
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();

        return view('home', compact('playingNow', 'upcoming'));
    }
}
