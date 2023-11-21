<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('admin', function (){
    return view('admin.layout.master');
});

Route::get('/', function (){
    return view('client.layout.master');
});

Route::get('home',function(){
    return view('client.pages.home');
});

Route::get('shop-grid',function(){
    return view('client.pages.shop-grid');
});

Route::get('shop-detail',function(){
    return view('client.pages.shop-detail');
});

