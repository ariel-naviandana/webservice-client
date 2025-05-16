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

    public function index()
    {
        // Ambil data film dari API lokal
        $response = Http::get(url: "{$this->baseUrl}/films");
        $responseCasts = Http::get(url: "{$this->baseUrl}/casts");
        $responseGenres = Http::get(url: "{$this->baseUrl}/genres");

        // Jika gagal, redirect kembali dengan pesan error
        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Gagal mengambil data film');
        }

        // Ambil isi JSON dan ubah menjadi collection
        $films = collect($response->json())->map(function ($film) {
            // Cek dan format poster_url agar menjadi URL lengkap
            $film['poster_url'] = !empty($film['poster_url'])
                ? (Str::startsWith($film['poster_url'], 'http')
                    ? $film['poster_url']
                    : 'https://image.tmdb.org/t/p/w500' . $film['poster_url'])
                : 'https://via.placeholder.com/300x450?text=No+Image';
            return $film;
        });

        if ($responseCasts->successful()) {
            $casts = $responseCasts->json();
        }

        if ($responseGenres->successful()) {
            $genres = $responseGenres->json();
        }

        // Filter berdasarkan genre jika ada
        // if (request('genre')) {
        //     $films = $films->filter(function ($film) {
        //         return isset($film['genre']) && $film['genre'] === request('genre');
        //     });
        // }

        // // Filter berdasarkan cast jika ada
        // if (request('cast')) {
        //     $films = $films->filter(function ($film) {
        //         return isset($film['cast']) && in_array(request('cast'), $film['cast']);
        //     });
        // }

        // // Ambil semua genre unik untuk ditampilkan di filter
        // $genres = $films->pluck('genre')->unique()->filter()->values()->all();

        // // Ambil semua cast unik
        // $casts = $films
        //     ->pluck('cast')
        //     ->flatten()
        //     ->unique()
        //     ->filter()
        //     ->values()
        //     ->all();

        // Kirim ke view
        return view('welcome', [
            'films' => $films,
            'genres' => $genres,
            'casts' => $casts,
        ]);
    }
}