<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;

Route::get('/', [ExampleController::class, 'homepage']);


Route::get('/about', [ExampleController::class, 'aboutpage']);
