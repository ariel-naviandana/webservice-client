<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class EditProfileController extends Controller
{
    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userId = Session::get('user_id');
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            try {
                $cloudinaryUrl = 'https://api.cloudinary.com/v1_1/dto6d9tbe/image/upload';
                $cloudinaryPreset = 'projek-tis';

                $response = Http::withOptions([
                    'verify' => false,
                ])->attach(
                    'file',
                    file_get_contents($request->file('photo')->getRealPath()),
                    $request->file('photo')->getClientOriginalName()
                )->post($cloudinaryUrl, [
                    'upload_preset' => $cloudinaryPreset,
                    'folder' => 'user_profiles',
                ]);

                if ($response->successful()) {
                    $data['image'] = $response->json()['secure_url'];
                } else {
                    return redirect()->route('editprofile')->with('error', 'Gagal mengunggah gambar ke Cloudinary.');
                }
            } catch (\Exception $e) {
                return redirect()->route('editprofile')->with('error', 'Gagal mengunggah gambar ke Cloudinary: ' . $e->getMessage());
            }
        }

        $response = Http::put("http://localhost:8000/api/users/{$userId}", $data);

        if ($response->successful()) {
            Session::put('user_name', $request->name);
            Session::put('user_email', $request->email);
            if (isset($data['image'])) {
                Session::put('user_photo', $data['image']);
            }
            return redirect()->route('editprofile')->with('success', 'Profil berhasil diperbarui.');
        } else {
            $error = $response->json('message') ?? 'Gagal memperbarui profil. Silakan coba lagi.';
            return redirect()->route('editprofile')->with('error', $error);
        }
    }
}
