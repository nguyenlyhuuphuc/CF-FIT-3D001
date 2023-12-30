<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('check.is.admin')->name('admin.')->group(function(){
    //Product Category
    Route::controller(ProductCategoryController::class)->group(function(){
        Route::get('product_category', 'index')->name('product_category');
        
        Route::get('product_category/create', 'create')->name('product_category.create');
        
        Route::post('product_category/store', 'store')->name('product_category.store');
        
        Route::post('product_category/slug', 'createSlug')->name('product_category.slug');
        
        Route::post('product_category/destroy/{id}', 'destroy')->name('product_category.destroy');
        
        Route::get('product_category/detail/{id}', 'detail')->name('product_category.detail');
        
        Route::post('product_category/update/{id}', 'update')->name('product_category.update');
    });

    Route::resource('product', ProductController::class);

    Route::post('product-upload-image',[ProductController::class, 'uploadImage'])->name('product.image.upload');

    Route::post('product/restore/{id}', [ProductController::class, 'restore'])->name('product.store');
    Route::post('product/force-delete/{id}', [ProductController::class, 'forceDelete'])->name('product.force.delete');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});