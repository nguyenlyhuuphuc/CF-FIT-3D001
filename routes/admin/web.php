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

Route::post('admin/product_category/slug', [ProductCategoryController::class, 'createSlug'])
->name('admin.product_category.slug');

Route::post('admin/product_category/destroy/{id}', [ProductCategoryController::class, 'destroy'])
->name('admin.product_category.destroy');

Route::get('admin/product_category/detail/{productCategory}', [ProductCategoryController::class, 'detail'])
->name('admin.product_category.detail');