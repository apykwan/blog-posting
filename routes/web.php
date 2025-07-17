<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\{UserController, PostController, FollowController};
use App\events\ChatMessage;
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

// Route::post('/send-chat-message', function (Request $request) {
//   $formFields = $request->validate([
//     'textvalue' => 'required'
//   ]);

//   if (!trim(strip_tags($formFields['textvalue']))) {
//     return response()->noContent();
//   }

//   Log::info('Send chat message called', ['text' => $request->textvalue]);

  // broadcast(new ChatMessage([
  //   'username' => Auth::user()->username, 
  //   'textvalue' => strip_tags($request->textvalue), 
  //   'avatar' => Auth::user()->avatar])
  // )->toOthers();
//   return response()->noContent();
// })->middleware('mustBeLoggedIn');


Route::post('/send-chat-message', function (Request $request) {
  $formFields = $request->validate([
    'textvalue' => 'required'
  ]);

  if (!trim(strip_tags($formFields['textvalue']))) {
    return response()->noContent();
  }

  $data = [
    'username' => Auth::user()->username,
    'textvalue' => strip_tags($request->textvalue),
    'avatar' => Auth::user()->avatar
  ];

  $port = env('NODE_SERVER_PORT', 5001);
  $ch = curl_init("http://localhost:{$port}/send-chat-message");
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);

  if ($response === false) {
    return response()->json(['error' => curl_error($ch)], 500);
  }

  curl_close($ch);
  return response()->json(['status' => 'Message sent', 'node_response' => $response]);
})->middleware('mustBeLoggedIn');

Route::get('/test-broadcast', function () {
  event(new ChatMessage([
    'username' => 'TestUser',
    'avatar' => 'https://example.com/avatar.png',
    'textvalue' => 'Hello from test route!',
  ]));

  return 'Broadcast event fired';
});