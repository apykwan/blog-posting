<?php

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{UserController, PostController, FollowController};

Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, 'storeAvatarForm'])->middleware('mustBeLoggedIn');

Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'deleteFollow'])->middleware('mustBeLoggedIn');

Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');
Route::get('/search/{term}', [PostController::class, 'search']);

Route::get('/profile/{user:username}', [UserController::class, 'profile']);
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers']);
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing']);

Route::middleware('cache.headers:public;max_age=20;etag')->group(function() {
  Route::get('/profile/{user:username}/raw', [UserController::class, 'profileRaw']);
  Route::get('/profile/{user:username}/followers/raw', [UserController::class, 'profileFollowersRaw']);
  Route::get('/profile/{user:username}/following/raw', [UserController::class, 'profileFollowingRaw']);
});

// For testing purposes
Route::get('/redis-check-set', function () {
  Redis::select(0);
  $members = Redis::smembers('users');
  return response()->json($members);
});

Route::get('/redis-keys', function () {
  Redis::select(0);
  $keys = Redis::keys('*');
  return response()->json($keys);
});

Route::get('/redis-test-info', function () {
  $info = Redis::info();
  return response()->json($info);
});

Route::get('/debug-session', function () {
  return [
    'session_id' => session()->getId(),
    'user_id' => Auth::id(),
    'session_exists' => \DB::table('sessions')->where('id', session()->getId())->exists(),
  ];
});

Route::get('/admin-only', function () {
  return 'Only admins should be to see this page';
})->middleware('can:visitAdminPages');
