<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserController::class, 'index'])
        ->name('dashboard');

    // Delete User
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';