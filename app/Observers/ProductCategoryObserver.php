<?php

namespace App\Observers;

use App\Mail\ProductCategoryMail;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Mail;

class ProductCategoryObserver
{
    /**
     * Handle the ProductCategory "created" event.
     */
    public function created(ProductCategory $productCategory): void
    {
        Mail::to('nguyenlyhuuphuc@gmail.com')->send(new ProductCategoryMail($productCategory));
    }

    /**
     * Handle the ProductCategory "updated" event.
     */
    public function updated(ProductCategory $productCategory): void
    {
        //dirty()
    }

    /**
     * Handle the ProductCategory "deleted" event.
     */
    public function deleted(ProductCategory $productCategory): void
    {
       
    }

    /**
     * Handle the ProductCategory "restored" event.
     */
    public function restored(ProductCategory $productCategory): void
    {
        //
    }

    /**
     * Handle the ProductCategory "force deleted" event.
     */
    public function forceDeleted(ProductCategory $productCategory): void
    {
         //remove all product base con product category
        //  $products = Product::where('product_category_id', $productCategory->id)->get();
        //  foreach($products as $product){
        //      $product->forceDelete();
        //  }

        $productCategory->products()->forceDelete();
    }
}
