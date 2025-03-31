<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageBooksController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\CanManageBooks;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'show'])->name('welcome');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/book/details/{book}', [BookController::class, 'show'])->name('book.show');
    Route::put('/book/checkout/{book}', [BookController::class, 'checkout'])->name('book.checkout');
    Route::post('/review/update', [ReviewController::class, 'store'])->name('review.store');
});

// Only allow access if user can manage books, by virtue of their role
Route::middleware('auth', 'verified', CanManageBooks::class)->group(function () {
    Route::get('/books/manage', [ManageBooksController::class, 'show'])->name('books.manage');
    Route::put('/book/return/{book}', [BookController::class, 'return'])->name('book.return');
    Route::delete('/book/delete/{book}', [BookController::class, 'destroy'])->name('book.destroy');
    Route::get('/book/edit/{book?}', [BookController::class, 'edit'])->name('book.edit');
    Route::post('/book/update', [BookController::class, 'store'])->name('book.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
