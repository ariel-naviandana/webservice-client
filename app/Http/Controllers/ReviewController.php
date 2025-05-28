<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    private $apiBaseUrl;
    private $reviewsApiUrl;
    private $usersApiUrl;
    private $filmsApiUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
        $this->reviewsApiUrl = "{$this->apiBaseUrl}/reviews";
        $this->usersApiUrl = "{$this->apiBaseUrl}/users";
        $this->filmsApiUrl = "{$this->apiBaseUrl}/films";
    }

    public function index()
    {
        $userId = Session::get('user_id');
        $token = Session::get('api_token');
        try {
            $response = Http::withToken($token)->get($this->reviewsApiUrl);
            if (!$response->successful()) {
                $error = $response->json('message') ?? 'Gagal mengambil data review';
                return redirect()->route('welcome')->with('error', $error);
            }
            $allReviews = $response->json();
            $userReviews = collect($allReviews)->filter(function ($review) use ($userId) {
                return isset($review['user']['id']) && $review['user']['id'] == $userId;
            })->values();
            return view('reviews.index', ['reviews' => $userReviews]);
        } catch (\Exception $e) {
            return redirect()->route('welcome')->with('error', 'Terjadi kesalahan saat mengambil data review');
        }
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
        try {
            $userResponse = Http::withToken($token)->get("{$this->usersApiUrl}/{$userId}");
            if (!$userResponse->successful()) {
                $error = $userResponse->json('message') ?? 'Gagal mengambil data pengguna.';
                return back()->withErrors([$error]);
            }
            $user = $userResponse->json();
            $isCritic = isset($user['role']) && $user['role'] === 'critic';

            $response = Http::withToken($token)->post($this->reviewsApiUrl, [
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

            $error = $response->json('message') ?? 'Gagal menyimpan review.';
            if ($response->json('errors')) {
                $error .= ' '.collect($response->json('errors'))->flatten()->join(' ');
            }
            return back()->withErrors([$error]);
        } catch (\Exception $e) {
            return back()->withErrors(['Terjadi kesalahan saat menyimpan review.']);
        }
    }

    public function edit($id)
    {
        $token = Session::get('api_token');
        $userId = Session::get('user_id');
        try {
            $response = Http::withToken($token)->get("{$this->reviewsApiUrl}/{$id}");
            if (!$response->successful()) {
                $error = $response->json('message') ?? 'Gagal mengambil data review';
                return redirect()->route('reviews.index')->with('error', $error);
            }
            $review = $response->json();
            if ($review['user_id'] != $userId) {
                return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki izin untuk mengedit review ini.');
            }
            return view('reviews.edit', ['review' => (object) $review]);
        } catch (\Exception $e) {
            return redirect()->route('reviews.index')->with('error', 'Terjadi kesalahan saat mengambil data review');
        }
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
        try {
            $reviewResponse = Http::withToken($token)->get("{$this->reviewsApiUrl}/{$id}");
            if (!$reviewResponse->successful() || $reviewResponse->json()['user_id'] != $userId) {
                return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki izin untuk mengedit review ini.');
            }

            $userResponse = Http::withToken($token)->get("{$this->usersApiUrl}/{$userId}");
            if (!$userResponse->successful()) {
                $error = $userResponse->json('message') ?? 'Gagal mengambil data pengguna.';
                return back()->withErrors([$error]);
            }
            $user = $userResponse->json();
            $isCritic = isset($user['role']) && $user['role'] === 'critic';

            $response = Http::withToken($token)->put("{$this->reviewsApiUrl}/{$id}", [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'is_critic' => $isCritic,
            ]);

            if ($response->successful()) {
                return redirect()->route('films.show', ['id' => $validated['film_id']])
                    ->with('success', 'Review berhasil diperbarui.');
            }

            $error = $response->json('message') ?? 'Gagal memperbarui review.';
            if ($response->json('errors')) {
                $error .= ' '.collect($response->json('errors'))->flatten()->join(' ');
            }
            return back()->withErrors([$error]);
        } catch (\Exception $e) {
            return back()->withErrors(['Terjadi kesalahan saat memperbarui review.']);
        }
    }

    public function destroy($id)
    {
        $token = Session::get('api_token');
        $userId = Session::get('user_id');
        try {
            $reviewResponse = Http::withToken($token)->get("{$this->reviewsApiUrl}/{$id}");
            if (!$reviewResponse->successful() || $reviewResponse->json()['user_id'] != $userId) {
                return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki izin untuk menghapus review ini.');
            }

            $response = Http::withToken($token)->delete("{$this->reviewsApiUrl}/{$id}");
            if ($response->successful()) {
                return redirect()->back()->with('success', 'Review berhasil dihapus.');
            }
            $error = $response->json('message') ?? 'Gagal menghapus review.';
            return redirect()->back()->with('error', $error);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus review.');
        }
    }
}
