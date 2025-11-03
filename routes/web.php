<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Public welcome page
Route::get('/', fn() => view('welcome'));

// Dashboard (GET = show, POST = handle form)
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth'])
    ->name('dashboard');

Route::post('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth']);

// Protected routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🆕 Post CRUD routes (optional if you use a PostController)
    Route::resource('posts', PostController::class);
});

require __DIR__.'/auth.php';
p