<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/category', [App\Http\Controllers\ProductController::class, 'index'])->name('category');
Route::get('/product', [App\Http\Controllers\ProductController::class, 'show'])->name('product');


Route::get('/customer', [App\Http\Controllers\PartnerController::class, 'index'])->name('customer');
Route::get('/supplier', [App\Http\Controllers\PartnerController::class, 'show'])->name('supplier');

