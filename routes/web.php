<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::post('/cart/add/{id}', [ProductsController::class, 'addToCart'])->name('products.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'removeCart'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');




