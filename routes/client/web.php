<?php

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function (){
    return view('client.layout.master');
})->name('home');

Route::get('home', [HomeController::class, 'index'])->name('home');

Route::get('shop-grid', function(){
    return view('client.pages.shop-grid');
});

Route::get('shop-detail',function(){
    return view('client.pages.shop-detail');
});

Route::get('cart/add-item/{id}/{qty?}', [CartController::class, 'add'])
->name('cart.add.item')->middleware('auth');
Route::get('cart/delete-item/{id}', [CartController::class, 'delete'])
->name('cart.delete.item')->middleware('auth');
Route::get('cart/remove-cart', [CartController::class, 'remove'])
->name('cart.remove.cart')->middleware('auth');
Route::get('cart/update-item/{id}/{qty?}', [CartController::class, 'update'])
->name('cart.update.item')->middleware('auth');

Route::get('cart', [CartController::class, 'index'])->name('cart.index');

Route::get('shop-detail/{slug}', [ProductController::class, 'getBySlug'])->name('product.get.by.slug');

