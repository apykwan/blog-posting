<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\{UserController, PostController, FollowController};
use Illuminate\Support\Facades\Log;

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
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, 'storeAvatarForm'])->middleware('mustBeLoggedIn');

// Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('mustBeLoggedIn');
// Route::post('/remove-follow/{user:username}', [FollowController::class, 'deleteFollow'])->middleware('mustBeLoggedIn');

Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
// Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
// Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
// Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');
Route::get('/search/{term}', [PostController::class, 'search']);

Route::get('/profile/{user:username}', [UserController::class, 'profile']);
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers']);
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing']);

Route::post('/send-chat-message', function (Request $request) {
  $formFields = $request->validate([
    'textvalue' => ['required', 'string']
  ]);

  if (!trim(strip_tags($formFields['textvalue']))) {
    return response()->json(["validated" => false]);
  }

  try {
    $data = [
      "avatar" => Auth::user()->avatar,
      "username" => Auth::user()->username,
      "textvalue" => $formFields['textvalue']
    ];

    $response = Http::post('http://localhost:' . env('NODE_SERVER_PORT', 5001) . '/send-chat-message', $data);

    if ($response->failed()) {
      Log::error('Failed to send message to socket server:', ['error' => $response->json()]);
    }
  } catch (\Exception $e) {
    Log::error('Exception while sending message to socket server:', ['error' => $e->getMessage()]);
  }

  return response()->json(['success' => true]);
})->middleware('mustBeLoggedIn');