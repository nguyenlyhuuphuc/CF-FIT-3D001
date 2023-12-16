<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getBySlug($slug){
        $product = Product::where('slug', $slug)->first();

        return view('client.pages.shop-detail')->with('product', $product);
    }
}
