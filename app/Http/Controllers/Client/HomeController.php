<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        //Get 8 latest product
        $featuredProducts = Product::latest()->take(8)->get();

        return view('client.pages.home')->with('featuredProducts', $featuredProducts);
    }
}
