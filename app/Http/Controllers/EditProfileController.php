<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class EditProfileController extends Controller
{
    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Ganti dengan auth()->user() jika sudah login
        $user = User::find(1);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Proses upload foto profil
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo_url && Storage::exists('public/' . $user->photo_url)) {
                Storage::delete('public/' . $user->photo_url);
            }

            $path = $request->file('photo')->store('profile_photos', 'public');
            $user->photo_url = $path;
        }

        $user->save();

        // Simpan ke session untuk digunakan di tampilan
        session([
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_photo' => $user->photo_url,
        ]);

        return redirect()->route('editprofile')->with('success', 'Edit profil sukses.');
    }
}
