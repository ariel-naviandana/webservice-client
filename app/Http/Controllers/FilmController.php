<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index()
    {
        // $movies = Film::with('casts')->get();
        $movies = Film::all();
        return view('welcome', compact('movies'));
    }
}
