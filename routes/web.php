<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{UserController, PostController};

Route::get('/debug-session', function () {
  return [
    'session_id' => session()->getId(),
    'user_id' => Auth::id(),
    'session_exists' => \DB::table('sessions')->where('id', session()->getId())->exists(),
  ];
});

Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('guest');
Route::post('/login', [UserController::class, 'login'])->name('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');

Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);

Route::get('/profile/{user}', [UserController::class, 'profile']);