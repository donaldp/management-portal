<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [\App\Http\Controllers\PersonController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'people', 'middleware' => ['auth']], function () {
    Route::get('/create', [\App\Http\Controllers\PersonController::class, 'create'])->name('people.create');
    Route::put('/create', [\App\Http\Controllers\PersonController::class, 'store'])->name('people.store');
    Route::get('/{person:id}', [\App\Http\Controllers\PersonController::class, 'show'])->name('people.show');
    Route::patch('/{person:id}', [\App\Http\Controllers\PersonController::class, 'update'])->name('people.update');
    Route::patch('/{person:id}/archive', [\App\Http\Controllers\PersonController::class, 'archive'])->name('people.archive');
    Route::patch('/{person:id}/unarchive', [\App\Http\Controllers\PersonController::class, 'unarchive'])->name('people.unarchive');
});

require __DIR__.'/auth.php';
