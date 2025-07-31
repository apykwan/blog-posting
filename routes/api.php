<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:jwt')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/send-chat-message', function (Request $request) {
    $formFields = $request->validate([
        'textvalue' => ['required', 'string']
    ]);

    if (!trim(strip_tags($formFields['textvalue']))) {
        return response()->json(["validated" => false]);
    }

    try {
        $data = [
            "userId" => Auth::user()->id,
            "username" => Auth::user()->username,
            'avatar' => Auth::user()->avatar,
            "textvalue" => $formFields['textvalue']
        ];

        $response = Http::post('http://localhost:' . env('NODE_SERVER_PORT', 5001) . '/api/send-chat-message', $data);

        if ($response->failed()) {
            Log::error('Failed to send message to socket server:', ['error' => $response->json()]);
        }
    } catch (\Exception $e) {
        Log::error('Exception while sending message to socket server:', ['error' => $e->getMessage()]);
    }

    return response()->json(['success' => true]);
})->middleware('auth:jwt');

Route::get('/get-chat-messages', function (Request $request) {
    try {
        $response = Http::get('http://localhost:' . env('NODE_SERVER_PORT', 5001) . '/api/get-chat-messages');

        return $response;
    } catch (\Exception $e) {
        Log::error('Exception while sending message to socket server:', ['error' => $e->getMessage()]);
    }
})->middleware('auth:jwt');


// Route::post('/jwt-test', [UserController::class, 'testJWTApi']);
// Route::get('/jwt-test', [UserController::class, 'testDecodeJWTApi']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/login', [UserController::class, 'loginApi']);
// Route::post('/create-post', [PostController::class, 'storeNewPostApi'])->middleware('auth:sanctum');
// Route::delete('/delete-post/{post}', [PostController::class, 'deleteApi'])->middleware('auth:sanctum');
