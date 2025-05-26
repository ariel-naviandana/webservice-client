<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FilmController extends Controller
{
    private $apiBaseUrl;
    private $filmsApiUrl;
    private $reviewsApiUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
        $this->filmsApiUrl = "{$this->apiBaseUrl}/films";
        $this->reviewsApiUrl = "{$this->apiBaseUrl}/reviews";
    }

    public function index()
    {
        $token = Session::get('api_token');
        $response = Http::withToken($token)->get($this->filmsApiUrl);

        if ($response->successful()) {
            $films = $response->json();
            return view('films.index', compact('films'));
        }

        return redirect()->route('welcome')->with('error', 'Gagal mengambil data film');
    }

    public function show($id)
    {
        $token = Session::get('api_token');
        $response = Http::withToken($token)->get("{$this->filmsApiUrl}/{$id}");

        if (!$response->successful()) {
            return redirect()->route('films.index')->with('error', 'Gagal mengambil detail film');
        }

        $film = $response->json();
        $userId = Session::get('user_id');

        $userReview = null;
        if ($userId) {
            $userReview = collect($film['reviews'])->firstWhere('user_id', $userId);
        }

        $ratings = array_column($film['reviews'], 'rating');
        $rating_avg = count($ratings) ? round(array_sum($ratings) / count($ratings), 1) : 0;
        $total_reviews = count($film['reviews']);

        return view('films.show', [
            'film' => $film,
            'userReview' => $userReview,
            'rating_avg' => $rating_avg,
            'total_reviews' => $total_reviews,
        ]);
    }
}
