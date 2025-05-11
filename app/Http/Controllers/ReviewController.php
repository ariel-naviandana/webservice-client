<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReviewController extends Controller
{
    protected $apiUrl = 'http://localhost:8000/api/reviews'; // ganti dengan URL REST API-mu
    protected $filmsApiUrl = 'http://localhost:8000/api/films'; // ganti dengan URL API film

    public function index()
    {
        $userId = session('user_id');

        $response = Http::get($this->apiUrl);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Gagal mengambil data review');
        }

        $allReviews = $response->json();

        // Filter review berdasarkan user yang sedang login
        $userReviews = collect($allReviews)->filter(function ($review) use ($userId) {
            return isset($review['user']['id']) && $review['user']['id'] == $userId;
        })->values(); // reset indeks array

        return view('reviews.index', ['reviews' => $userReviews]);
    }

    public function create()
    {
        // Mengambil data film
        $films = Http::get($this->filmsApiUrl)->json();
        return view('reviews.create', compact('films'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'film_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:10',
            'comment' => 'nullable|string',
            'is_critic' => 'nullable|boolean',
        ]);

        $data['user_id'] = session('user_id');
        $data['is_critic'] = $request->has('is_critic');

        $response = Http::post($this->apiUrl, $data);

        if (!$response->successful()) {
            return back()->with('error', 'Gagal menambahkan review');
        }

        return redirect()->route('reviews.index')->with('success', 'Review berhasil ditambahkan');
    }

    public function edit($id)
    {
        $review = Http::get("{$this->apiUrl}/{$id}")->json();

        // Mengambil data film untuk dropdown
        $films = Http::get($this->filmsApiUrl)->json();

        return view('reviews.edit', compact('review', 'films'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'comment' => 'nullable|string',
            'is_critic' => 'nullable|boolean',
        ]);

        $data['is_critic'] = $request->has('is_critic');

        $response = Http::put("{$this->apiUrl}/{$id}", $data);

        if (!$response->successful()) {
            return back()->with('error', 'Gagal memperbarui review');
        }

        return redirect()->route('reviews.index')->with('success', 'Review berhasil diperbarui');
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->apiUrl}/{$id}");

        if (!$response->successful()) {
            return back()->with('error', 'Gagal menghapus review');
        }

        return redirect()->route('reviews.index')->with('success', 'Review berhasil dihapus');
    }
}
