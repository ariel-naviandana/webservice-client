<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    protected $baseUrl = 'http://localhost:8000/api';

    public function index(Request $request)
    {
        $genreFilter = $request->input('genre');
        $castFilter = $request->input('cast');

        $response = Http::get("{$this->baseUrl}/films");
        $responseCasts = Http::get("{$this->baseUrl}/casts");
        $responseGenres = Http::get("{$this->baseUrl}/genres");

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Gagal mengambil data film');
        }

        $films = collect($response->json())->map(function ($film) {
            $film['poster_url'] = !empty($film['poster_url'])
                ? (Str::startsWith($film['poster_url'], 'http')
                    ? $film['poster_url']
                    : 'https://image.tmdb.org/t/p/w500' . $film['poster_url'])
                : 'https://via.placeholder.com/300x450?text=No+Image';
            return $film;
        });

        // FILTER by genre
        if ($genreFilter) {
            $films = $films->filter(function ($film) use ($genreFilter) {
                return collect($film['genres'])->pluck('name')->contains($genreFilter);
            });
        }

        // FILTER by cast
        if ($castFilter) {
            $films = $films->filter(function ($film) use ($castFilter) {
                return collect($film['characters'])->pluck('name')->contains($castFilter);
            });
        }

        return view('welcome', [
            'films' => $films,
            'genres' => $responseGenres->successful() ? $responseGenres->json() : [],
            'casts' => $responseCasts->successful() ? $responseCasts->json() : [],
        ]);
    }
}