<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login_form');
Route::post('/login_process', [AuthController::class, 'loginProcess'])->name('login_process');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register_form');
Route::post('/register_process', [AuthController::class, 'registerProcess'])->name('register_process');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
