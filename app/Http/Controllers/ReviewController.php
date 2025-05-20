<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    protected $apiUrl = 'http://localhost:8000/api/reviews';
    protected $filmsApiUrl = 'http://localhost:8000/api/films';
    protected $usersApiUrl = 'http://localhost:8000/api/users';

    public function index()
    {
        $userId = Session::get('user_id');
        $token = Session::get('api_token');

        $response = Http::withToken($token)->get($this->apiUrl);

        if (!$response->successful()) {
            return redirect()->route('welcome')->with('error', 'Gagal mengambil data review');
        }

        $allReviews = $response->json();
        $userReviews = collect($allReviews)->filter(function ($review) use ($userId) {
            return isset($review['user']['id']) && $review['user']['id'] == $userId;
        })->values();

        return view('reviews.index', ['reviews' => $userReviews]);
    }

    public function create(Request $request)
    {
        $filmId = $request->query('film_id');
        return view('reviews.create', compact('filmId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:10',
            'comment' => 'required|string',
        ]);

        $userId = Session::get('user_id');
        $token = Session::get('api_token');

        $userResponse = Http::withToken($token)->get("{$this->usersApiUrl}/{$userId}");

        if (!$userResponse->successful()) {
            return back()->withErrors(['Gagal mengambil data pengguna.']);
        }

        $user = $userResponse->json();
        $isCritic = isset($user['role']) && $user['role'] === 'critic';

        $response = Http::withToken($token)->post($this->apiUrl, [
            'user_id' => $userId,
            'film_id' => $validated['film_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_critic' => $isCritic,
        ]);

        if ($response->successful()) {
            return redirect()->route('films.show', ['id' => $validated['film_id']])
                ->with('success', 'Review berhasil disimpan.');
        }

        return back()->withErrors(['Gagal menyimpan review.']);
    }

    public function edit($id)
    {
        $token = Session::get('api_token');
        $userId = Session::get('user_id');

        $response = Http::withToken($token)->get("{$this->apiUrl}/{$id}");

        if (!$response->successful()) {
            return redirect()->route('reviews.index')->with('error', 'Gagal mengambil data review');
        }

        $review = $response->json();

        if ($review['user_id'] != $userId) {
            return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki izin untuk mengedit review ini.');
        }

        return view('reviews.edit', ['review' => (object) $review]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'film_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:10',
            'comment' => 'required|string',
        ]);

        $userId = Session::get('user_id');
        $token = Session::get('api_token');

        // Check ownership
        $reviewResponse = Http::withToken($token)->get("{$this->apiUrl}/{$id}");
        if (!$reviewResponse->successful() || $reviewResponse->json()['user_id'] != $userId) {
            return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki izin untuk mengedit review ini.');
        }

        $userResponse = Http::withToken($token)->get("{$this->usersApiUrl}/{$userId}");

        if (!$userResponse->successful()) {
            return back()->withErrors(['Gagal mengambil data pengguna.']);
        }

        $user = $userResponse->json();
        $isCritic = isset($user['role']) && $user['role'] === 'critic';

        $response = Http::withToken($token)->put("{$this->apiUrl}/{$id}", [
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_critic' => $isCritic,
        ]);

        if ($response->successful()) {
            return redirect()->route('films.show', ['id' => $validated['film_id']])
                ->with('success', 'Review berhasil diperbarui.');
        }

        return back()->withErrors(['Gagal memperbarui review.']);
    }

    public function destroy($id)
    {
        $token = Session::get('api_token');
        $userId = Session::get('user_id');

        // Check ownership
        $reviewResponse = Http::withToken($token)->get("{$this->apiUrl}/{$id}");
        if (!$reviewResponse->successful() || $reviewResponse->json()['user_id'] != $userId) {
            return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki izin untuk menghapus review ini.');
        }

        $response = Http::withToken($token)->delete("{$this->apiUrl}/{$id}");

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Review berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus review.');
    }
}
