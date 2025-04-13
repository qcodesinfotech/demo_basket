<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BasketController;

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route - protected by auth + verified middleware
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes - protected by auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Product routes (public)
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
});

// Basket routes - protected by auth
Route::middleware('auth')->prefix('basket')->name('basket.')->group(function () {
    Route::get('/', [BasketController::class, 'index'])->name('index');
    Route::post('/add', [BasketController::class, 'add'])->name('store'); // REST-style name
    Route::delete('/clear', [BasketController::class, 'clear'])->name('destroy');
});

require __DIR__.'/auth.php';
