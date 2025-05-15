<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlist = Watchlist::with('film')->where('user_id', '1')->get();
        return view('watchlist', compact('watchlist'));
    }
    
    public function add(Film $movie)
    {
        $exists = Watchlist::where('user_id', "1")
                    ->where('film_id', $movie->id)
                    ->exists();

        if ($exists) {
            return redirect()->back()->with('info', 'Film sudah ada di watchlist kamu.');
        }

        Watchlist::create([
            'user_id' => "1",
            'film_id' => $movie->id,
        ]);

        return redirect()->back()->with('success', 'Film berhasil ditambahkan ke watchlist!');
    }
}
