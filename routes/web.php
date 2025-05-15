<?php

use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\WatchlistController;
use App\Models\Film;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', [FilmController::class, 'index']);
Route::get('/watchlist', [WatchlistController::class, 'index']);
Route::get('/watchlist/add/{movie}', [WatchlistController::class, 'add'])->name('watchlist.add');
Route::get('/edit-profile', function () {
    $user = User::find(1);
    return view('editprofile', compact('user'));
})->name('editprofile');
Route::post('/edit', [EditProfileController::class, 'edit'])->name('editprofile.edit');
