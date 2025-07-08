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

Route::get('/admin-only', function() {
  return 'Only admins should be to see this page';
})->middleware('can:visitAdminPages');

Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('guest');
Route::post('/login', [UserController::class, 'login'])->name('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm']);

Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');

Route::get('/profile/{user:username}', [UserController::class, 'profile']);
