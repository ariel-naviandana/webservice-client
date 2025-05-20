<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAuth
{
    public function handle(Request $request, Closure $next)
    {
        $isLoggedIn = Session::has('user_id');

        // If accessing login or register while logged in, redirect to /
        if ($isLoggedIn && in_array($request->route()->getName(), ['login_form', 'register_form'])) {
            return redirect()->route('welcome');
        }

        // If not logged in and accessing protected route, redirect to login
        if (!$isLoggedIn && !in_array($request->route()->getName(), ['login_form', 'login_process', 'register_form', 'register_process', 'welcome', 'films.index', 'films.show'])) {
            return redirect()->route('login_form')->with('message', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
