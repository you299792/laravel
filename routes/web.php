<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('users.index');
});

Route::resource('users', UserController::class);
Route::post('users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
