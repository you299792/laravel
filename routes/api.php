<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

/**
 * Authentication Routes
 */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/**
 * Protected Routes (require authentication)
 */
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // User routes
    Route::apiResource('users', UserController::class);
    Route::post('users/{id}/verify', [UserController::class, 'verify'])->name('users.verify');

    // Get current authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
