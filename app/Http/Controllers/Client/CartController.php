<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add($id){
        $cart = session()->get('cart', []);

        $product = Product::find($id);

        $cart[$id] = [
            'name' => $product->name,
            'qty' => ($cart[$id]['qty'] ?? 0) + 1,
            'price' => $product->price,
            'image' => asset('images'). '/' . $product->image_url,
        ];
        
        session()->put('cart', $cart);

        return response()->json([
            'message'=> 'Them san pham vao gio hang thanh cong',
            'numberItem' => count($cart)
        ]);
    }
}
