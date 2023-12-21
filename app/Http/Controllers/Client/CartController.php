<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderEvent;
use App\Http\Controllers\Controller;
use App\Mail\OrderAdminEmail;
use App\Mail\OrderClientEmail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function index(){
        $cart = session()->get('cart', []);

        return view('client.pages.cart')->with('cart', $cart);
    }

    public function checkout(){
        $cart = session()->get('cart', []);

        return view('client.pages.checkout')->with('cart', $cart);
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
            'numberItem' => count($cart),
            'totalPrice' => $this->calculateTotalPrice($cart)
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
            'numberItem' => count($cart),
            'totalPrice' => $this->calculateTotalPrice($cart)
        ]);
    }
    
    public function remove(){
        session()->put('cart', []);

        return response()->json([
            'message'=> 'Xoa gio hang thanh cong',
            'numberItem' => 0,
            'totalPrice' => 0
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
            'priceTotalItem' => $price,
            'totalPrice' => $this->calculateTotalPrice($cart)
        ]);
    }
    
    private function calculateTotalPrice($cart){
        $total = 0;
        foreach($cart as $item){
            $total += $item['price'] * $item['qty'];
        }
        return number_format($total, 2);
    }

    public function placeOrder(Request $request){
        try{
            DB::beginTransaction();

            $order = Order::create([
                'address' => $request->address,
                'notes' => $request->notes,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'user_id' => Auth::user()->id
            ]);

            $cart = session()->get('cart', []);
    
            $total = 0;
            foreach($cart as $productId => $item){
                $total += $item['qty'] * $item['price'];
                OrderItem::create([
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'order_id' => $order->id, 
                    'product_id' => $productId,
                ]);
            }
    
            //Update record order
            $order->subtotal = $total;
            $order->total = $total;
            $order->save();
    
            OrderPaymentMethod::create([
                'total' => $total,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'order_id' => $order->id
            ]);
    
            //Update user
            $user = User::find(Auth::user()->id);
            $user->phone = $request->phone;
            $user->save();
    
            session()->put('cart', []);

            DB::commit();

            //Tao su kien order event
            event(new OrderEvent($order));

            //Send email client
            // Mail::to('nguyenlyhuuphuc@gmail.com')->send(new OrderClientEmail($order));

            //Send email admin
            // Mail::to('nguyenlyhuuphuc@gmail.com')->send(new OrderAdminEmail($order));

            //Minus qty in product 

            return redirect()->route('home')->with('msg', 'Dat hang thanh cong');
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->route('home')->with('msg', 'Dat hang that bai');
        }
    }
}
