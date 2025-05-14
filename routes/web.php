<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FilmController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login_form');
Route::post('/login_process', [AuthController::class, 'loginProcess'])->name('login_process');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register_form');
Route::post('/register_process', [AuthController::class, 'registerProcess'])->name('register_process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('index');
    Route::get('/create', [ReviewController::class, 'create'])->name('create');
    Route::post('/', [ReviewController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ReviewController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ReviewController::class, 'update'])->name('update');
    Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
});

Route::get('/films', [FilmController::class, 'index'])->name('films.index');
Route::get('/films/{id}', [FilmController::class, 'show'])->name('films.show');
