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

        $films = $response->json();

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
