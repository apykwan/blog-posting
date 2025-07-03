<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ExampleController, UserController};

Route::get('/', [UserController::class, 'showCorrectHomepage']);
Route::get('/about', [ExampleController::class, 'aboutpage']);
Route::post('/register', [UserController::class, 'register']);

Route::post('/login', [UserController::class, 'login']);