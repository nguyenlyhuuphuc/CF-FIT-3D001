<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('admin', function (){
    return view('admin.layout.master');
});

Route::get('admin/product_category', [ProductCategoryController::class, 'index'])
->name('admin.product_category');

Route::get('admin/product_category/create', [ProductCategoryController::class, 'create'])
->name('admin.product_category.create');

Route::post('admin/product_category/store', [ProductCategoryController::class, 'store'])
->name('admin.product_category.store');

