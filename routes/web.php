<?php

use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\WatchlistController;
use App\Models\Film;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FilmController;

Route::get('/', function () {
    return view('welcome');
});
