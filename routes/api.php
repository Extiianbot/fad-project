<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;


// Route for user registration
Route::post('/register', [RegisterController::class, 'register']);

// Optional: route to get authenticated user info
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/register', function () {
    return response()->json(['message' => 'GET /api/register was called'], 405);
});

Route::post('/login', [LoginController::class, 'login']);

