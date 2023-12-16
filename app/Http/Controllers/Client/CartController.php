<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class CartController extends Controller
{
    public function index(){
        $cart = session()->get('cart', []);

        return view('client.pages.cart')->with('cart', $cart);
    }

    public function add($id, $qty = 1){
        $cart = session()->get('cart', []);

        $product = Product::find($id);

        $cart[$id] = [
            'name' => $product->name,
            'qty' => ($cart[$id]['qty'] ?? 0) + $qty,
            'price' => $product->price,
            'image' => asset('images'). '/' . $product->image_url,
        ];
        
        session()->put('cart', $cart);

        return response()->json([
            'message'=> 'Them san pham vao gio hang thanh cong',
            'numberItem' => count($cart)
        ]);
    }

    public function delete($id){
        $cart = session()->get('cart', []);

        if(array_key_exists($id, $cart)){
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'message'=> 'Xoa san pham thanh cong',
            'numberItem' => count($cart)
        ]);
    }
    
    public function remove(){
        session()->put('cart', []);

        return response()->json([
            'message'=> 'Xoa gio hang thanh cong',
            'numberItem' => 0
        ]);
    }

    public function update($id, $qty){
        $cart = session()->get('cart', []);

        if(array_key_exists($id, $cart)){
            $cart[$id]['qty'] = $qty;
            if(!$qty){
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }

        $price = $cart[$id]['price'] * $cart[$id]['qty'];

        return response()->json([
            'message'=> 'Cap nhat san pham thanh cong',
            'numberItem' => count($cart),
            'priceTotalItem' => $price
        ]);
    }
}
