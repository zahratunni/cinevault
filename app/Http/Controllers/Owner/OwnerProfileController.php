<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OwnerProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('owner.profile.index', compact('user'));
    }
}