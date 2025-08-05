<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;

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
   Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('index');
        Route::get('/data', [App\Http\Controllers\CategoryController::class, 'getData'])->name('data');
        Route::post('/', [App\Http\Controllers\CategoryController::class, 'store'])->name('store');
        Route::get('/{kategori}', [App\Http\Controllers\CategoryController::class, 'show'])->name('show');
        Route::put('/{kategori}', [App\Http\Controllers\CategoryController::class, 'update'])->name('update');
        Route::delete('/{kategori}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
        Route::get('/data', [App\Http\Controllers\ProductController::class, 'getData'])->name('data');
        Route::post('/', [App\Http\Controllers\ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('show');
        Route::put('/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/', [App\Http\Controllers\MitraController::class, 'index'])->name('index');
        Route::get('/data', [App\Http\Controllers\MitraController::class, 'getData'])->name('data');
        Route::post('/', [App\Http\Controllers\MitraController::class, 'store'])->name('store');
        Route::get('/{customer}', [App\Http\Controllers\MitraController::class, 'show'])->name('show');
        Route::put('/{customer}', [App\Http\Controllers\MitraController::class, 'update'])->name('update');
        Route::delete('/{customer}', [App\Http\Controllers\MitraController::class, 'destroy'])->name('destroy');
    });

    Route::get('/supplier', [App\Http\Controllers\PartnerController::class, 'show'])->name('supplier');
});