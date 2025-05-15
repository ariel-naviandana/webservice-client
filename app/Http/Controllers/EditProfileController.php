<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EditProfileController extends Controller
{
    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::find(1);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('editprofile')->with('success', 'Edit profil sukses.');
    }
}
