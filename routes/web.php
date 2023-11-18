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

Route::get('/', function () {
    return view('blade.view-blade');
});

Route::get('master', function (){
    return view('layout.master');
});

Route::get('product/index', function (){
    return view('product.index');
});

Route::get('product_category/index', function (){
    return view('product_category.index');
});



Route::get('product/create', function (){
    echo 'Tao san pham';
});

Route::get('product/detail/{id}/{productId?}', function ($id, $productId = null){
    echo "Detail san pham id : $id danh muc san pham : $productId";
});