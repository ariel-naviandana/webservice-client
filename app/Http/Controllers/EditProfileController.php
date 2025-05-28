<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class EditProfileController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function show()
    {
        $userId = Session::get('user_id');
        $token = Session::get('api_token');

        try {
            $response = Http::withToken($token)->get("{$this->apiBaseUrl}/users/{$userId}");
            if ($response->successful()) {
                $user = $response->json();
                return view('editprofile', compact('user'));
            }
            $error = $response->json('message') ?? 'Gagal mengambil data profil.';
            return redirect()->route('welcome')->with('error', $error);
        } catch (\Exception $e) {
            return redirect()->route('welcome')->with('error', 'Terjadi kesalahan saat mengambil data profil.');
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userId = Session::get('user_id');
        $token = Session::get('api_token');
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            try {
                $cloudinaryUrl = 'https://api.cloudinary.com/v1_1/dto6d9tbe/image/upload';
                $cloudinaryPreset = 'projek-tis';
                $maxRetries = 3;
                $retryDelay = 1000;
                $cloudinaryResponse = null;
                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    try {
                        $cloudinaryResponse = Http::withOptions([
                            'verify' => false,
                            'timeout' => 30,
                            'connect_timeout' => 10,
                        ])->attach(
                            'file',
                            file_get_contents($request->file('photo')->getRealPath()),
                            $request->file('photo')->getClientOriginalName()
                        )->post($cloudinaryUrl, [
                            'upload_preset' => $cloudinaryPreset,
                            'folder' => 'user_profiles',
                        ]);
                        if ($cloudinaryResponse->successful()) {
                            $data['image'] = $cloudinaryResponse->json()['secure_url'];
                            break;
                        }
                    } catch (\Exception $e) {
                        if ($attempt === $maxRetries) {
                            return redirect()->route('editprofile')->with('error', 'Gagal mengunggah gambar: ' . $e->getMessage());
                        }
                        sleep($retryDelay / 1000);
                    }
                }
                if (!$cloudinaryResponse->successful()) {
                    return redirect()->route('editprofile')->with('error', 'Gagal mengunggah gambar ke Cloudinary.');
                }
            } catch (\Exception $e) {
                return redirect()->route('editprofile')->with('error', 'Gagal mengunggah gambar: ' . $e->getMessage());
            }
        }

        try {
            $response = Http::withToken($token)->put("{$this->apiBaseUrl}/users/{$userId}", $data);
            if ($response->successful()) {
                Session::put('user_name', $request->name);
                Session::put('user_email', $request->email);
                if (isset($data['image'])) {
                    Session::put('user_photo', $data['image']);
                }
                return redirect()->route('editprofile')->with('success', 'Profil berhasil diperbarui.');
            }

            $error = $response->json('message') ?? 'Gagal memperbarui profil.';
            if ($response->json('errors')) {
                $error .= ' '.collect($response->json('errors'))->flatten()->join(' ');
            }
            return redirect()->route('editprofile')->with('error', $error);
        } catch (\Exception $e) {
            return redirect()->route('editprofile')->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }
}
