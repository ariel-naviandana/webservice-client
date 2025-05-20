<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    protected $baseUrl = 'http://localhost:8000/api';

    public function index(Request $request)
    {
        $genreFilter = $request->input('genre');
        $castFilter = $request->input('cast');
        $token = Session::get('api_token');

        $response = Http::withToken($token)->get("{$this->baseUrl}/films");
        $responseCasts = Http::withToken($token)->get("{$this->baseUrl}/casts");
        $responseGenres = Http::withToken($token)->get("{$this->baseUrl}/genres");

        if (!$response->successful()) {
            return redirect()->route('welcome')->with('error', 'Gagal mengambil data film');
        }

        $films = collect($response->json());

        if ($genreFilter) {
            $films = $films->filter(function ($film) use ($genreFilter) {
                return collect($film['genres'])->pluck('name')->contains($genreFilter);
            });
        }

        if ($castFilter) {
            $films = $films->filter(function ($film) use ($castFilter) {
                return collect($film['characters'])->pluck('name')->contains($castFilter);
            });
        }

        return view('welcome', [
            'films' => $films->values(),
            'genres' => $responseGenres->successful() ? $responseGenres->json() : [],
            'casts' => $responseCasts->successful() ? $responseCasts->json() : [],
        ]);
    }
}
