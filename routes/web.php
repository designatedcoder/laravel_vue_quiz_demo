<?php

use App\Http\Controllers\StatController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/quiz-stats', [StatController::class, 'index'])->name('stats.index');
    Route::post('/quiz-stats', [StatController::class, 'store'])->name('stats.store');
});

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
