<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function loginProcess(Request $request)
    {
        try {
            $response = Http::post("{$this->apiBaseUrl}/login", [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $user = $data['user'];
                $token = $data['token'];

                Session::put('user_id', $user['id']);
                Session::put('user_name', $user['name']);
                Session::put('user_email', $user['email']);
                Session::put('user_photo', $user['photo_url']);
                Session::put('user_role', $user['role']);
                Session::put('api_token', $token);

                return redirect()->route('welcome');
            } else {
                $errorMsg = $response->json('message') ?? 'Login gagal dilakukan.';
                return redirect()->back()->with('message', $errorMsg);
            }
        } catch (ConnectionException $e) {
            return redirect()->back()->with('message', 'Tidak dapat terhubung ke server API.');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan (internal). Silakan coba lagi.');
        }
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function registerProcess(Request $request)
    {
        try {
            $response = Http::post("{$this->apiBaseUrl}/register", [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                return redirect()->route('login_form')->with('message', 'Registrasi berhasil, silakan login.');
            } else {
                // Tampilkan pesan error validasi jika ada
                $error = $response->json('message') ?? 'Registrasi gagal.';
                if ($response->json('errors')) {
                    $error .= ' '.collect($response->json('errors'))->flatten()->join(' ');
                }
                return redirect()->back()->with('message', $error);
            }
        } catch (ConnectionException $e) {
            return redirect()->back()->with('message', 'Tidak dapat terhubung ke server API.');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Terjadi kesalahan (internal). Silakan coba lagi.');
        }
    }

    public function logout()
    {
        try {
            $response = Http::withToken(Session::get('api_token'))->post("{$this->apiBaseUrl}/logout");
            if ($response->successful()) {
                Session::flush();
                return redirect()->route('login_form')->with('message', 'Berhasil logout.');
            } else {
                return redirect()->route('welcome')->with('message', 'Gagal logout. Silakan coba lagi.');
            }
        } catch (ConnectionException $e) {
            return redirect()->route('welcome')->with('message', 'Tidak dapat terhubung ke server API.');
        } catch (\Exception $e) {
            return redirect()->route('welcome')->with('message', 'Terjadi kesalahan (internal) saat logout.');
        }
    }
}
