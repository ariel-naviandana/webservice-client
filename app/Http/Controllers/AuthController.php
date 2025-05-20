<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function loginProcess(Request $request)
    {
        try {
            $response = Http::post('http://localhost:8000/api/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->status() == 200) {
                $data = $response->json();
                $user = $data['user'];
                $token = $data['token'];

                // Store user data and token in session
                Session::put('user_id', $user['id']);
                Session::put('user_name', $user['name']);
                Session::put('user_email', $user['email']);
                Session::put('user_role', $user['role']);
                Session::put('api_token', $token);

                return redirect()->route('welcome');
            } else {
                return redirect()->back()->with('message', 'Registrasi gagal dilakukan.');
            }
        } catch (\Exception $e) {
            return redirect()->route('login_form')->with('message', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function registerProcess(Request $request)
    {
        $response = Http::post('http://localhost:8000/api/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->status() == 200) {
            return redirect()->route('login_form')->with('message', 'Registrasi berhasil, silakan login.');
        } else {
            $error = $response->json('message') ?? 'Registrasi gagal.';
            return redirect()->back()->with('message', 'Registrasi gagal: ' . $error);
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login_form')->with('message', 'Berhasil logout.');
    }
}
