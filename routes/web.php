<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageBooksController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\IsLibrarian;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'show'])->name('welcome');
Route::get('/dashboard', [DashboardController::class, 'show'])->middleware(['auth', 'verified'])->name('dashboard');
Route::put('/books/{book}', [ManageBooksController::class, 'checkout'])->name('book.checkout');

// Only allow access if user has librarian role
Route::middleware('auth', 'verified', IsLibrarian::class)->group(function () {
    Route::get('/manage-books', [ManageBooksController::class, 'show'])->name('manage-books');
    Route::put('/manage-books/{book}', [ManageBooksController::class, 'return'])->name('book.return');
    Route::delete('/manage-books/{book}', [ManageBooksController::class, 'destroy'])->name('book.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
