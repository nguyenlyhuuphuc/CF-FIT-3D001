<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function (){
    return view('client.layout.master');
});

Route::get('home', function(){
    return view('client.pages.home');
});
Route::get('shop-grid', function(){
    return view('client.pages.shop-grid');
});

Route::get('shop-detail',function(){
    return view('client.pages.shop-detail');
});

