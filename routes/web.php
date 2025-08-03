<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect to login if not authenticated, otherwise redirect to home
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Authentication routes
Auth::routes();

// Routes for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::get('/test', [App\Http\Controllers\HomeController::class, 'show'])->name('test');
    
    // FIXED: Move category routes inside auth middleware
    Route::get('/category', [App\Http\Controllers\ProductController::class, 'index'])->name('category');
    Route::post('/category', [App\Http\Controllers\ProductController::class, 'store'])->name('category.store');
    Route::get('/product', [App\Http\Controllers\ProductController::class, 'show'])->name('product');
    
    Route::get('/customer', [App\Http\Controllers\PartnerController::class, 'index'])->name('customer');
    Route::get('/supplier', [App\Http\Controllers\PartnerController::class, 'show'])->name('supplier');
});