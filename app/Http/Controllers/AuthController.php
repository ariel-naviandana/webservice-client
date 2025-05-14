<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\User; // Pastikan kamu punya model User

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Menampilkan halaman login
        return view('login');
    }

    public function loginProcess(Request $request)
    {
        // Validasi input form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            // Melakukan request ke API login
            $response = Http::post("http://localhost:8000/api/login", [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Mengecek apakah status response adalah 200 OK
            if ($response->status() == 200) {
                $user = $response->json('user');

                // Menyimpan data pengguna di session
                Session::put('user_id', $user['id']);
                Session::put('user_name', $user['name']);
                Session::put('user_email', $user['email']);
                Session::put('user_role', $user['role']);

                // Redirect ke halaman films.index setelah login berhasil
                return redirect()->route('films.index'); // Mengarahkan ke halaman films.index
            } else {
                // Jika login gagal, ambil pesan error dari API dan redirect kembali ke form login
                $error = $response->json('message') ?? 'Login gagal. Silakan coba lagi.';
                return redirect()->route('login_form')->with('message', $error);
            }
        } catch (\Exception $e) {
            // Menangani jika terjadi error selama proses request ke API
            return redirect()->route('login_form')->with('message', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function registerProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $response = Http::post('http://localhost:8000/api/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'critic'
        ]);

        if ($response->status() == 201) {
            return redirect()->route('login_form')->with('message', 'Registrasi berhasil, silakan login.');
        } else {
            return redirect()->back()->with('message', 'Registrasi gagal dilakukan.');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login_form')->with('message', 'Berhasil logout.');
    }
}
