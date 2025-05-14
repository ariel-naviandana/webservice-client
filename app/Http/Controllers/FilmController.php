<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session; // Import Session

class FilmController extends Controller
{
    protected $filmsApiUrl = 'http://localhost:8000/api/films'; // URL API film
    protected $reviewsApiUrl = 'http://localhost:8000/api/reviews'; // URL API review film

    public function index()
    {
        // Mengambil data film
        $response = Http::get($this->filmsApiUrl);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Gagal mengambil data film');
        }

        $films = $response->json();

        return view('films.index', compact('films'));
    }

    public function show($id)
    {
        // Mendapatkan data film
        $response = Http::get("{$this->filmsApiUrl}/{$id}");
        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Gagal mengambil detail film');
        }

        $film = $response->json();
        $userId = Session::get('user_id'); // Mendapatkan user_id dari session

        $userReview = null;

        // Jika pengguna sudah login, cek apakah sudah memberikan review untuk film ini
        if ($userId) {
            $userReview = collect($film['reviews'])->firstWhere('user_id', $userId);
        }

        // Menghitung rating rata-rata dan total review
        $ratings = array_column($film['reviews'], 'rating');
        $rating_avg = count($ratings) ? round(array_sum($ratings) / count($ratings), 1) : 0;
        $total_reviews = count($film['reviews']);

        return view('films.show', [
            'film' => $film,
            'userReview' => $userReview, // Menampilkan review pengguna yang sudah ada
            'rating_avg' => $rating_avg,
            'total_reviews' => $total_reviews,
        ]);
    }
}
