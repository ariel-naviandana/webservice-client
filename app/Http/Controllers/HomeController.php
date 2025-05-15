<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
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

        return view('welcome', compact('films'));
    }
}
