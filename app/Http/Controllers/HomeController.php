<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    protected $filmsApiUrl = 'http://localhost:8000/api/films'; // URL API film
    protected $reviewsApiUrl = 'http://localhost:8000/api/reviews'; // URL API review film

public function index()
{
    // Ambil data film dari API
    $response = Http::get($this->filmsApiUrl);

    if (!$response->successful()) {
        return redirect()->back()->with('error', 'Gagal mengambil data film');
    }

    $films = $response->json();

    // Filter berdasarkan query string (opsional)
    $filteredFilms = collect($films);

    if (request('genre')) {
        $filteredFilms = $filteredFilms->filter(function ($film) {
            return isset($film['genre']) && $film['genre'] === request('genre');
        });
    }

    if (request('cast')) {
        $filteredFilms = $filteredFilms->filter(function ($film) {
            return isset($film['cast']) && in_array(request('cast'), $film['cast']);
        });
    }

    // Ambil semua genre unik
    $genres = collect($films)->pluck('genre')->unique()->filter()->values()->all();

    // Ambil semua cast unik
    $casts = collect($films)
        ->pluck('cast')
        ->flatten()
        ->unique()
        ->filter()
        ->values()
        ->all();

    return view('welcome', [
        'films' => $filteredFilms,
        'genres' => $genres,
        'casts' => $casts,
    ]);
}


}
