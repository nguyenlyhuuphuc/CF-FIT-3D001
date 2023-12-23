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
use Illuminate\Support\Facades\Redirect;

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

            $paymentMethod = $request->payment_method;

            if($paymentMethod === 'VNBANK'){
                date_default_timezone_set('Asia/Ho_Chi_Minh');

                $vnp_TxnRef = (string)$order->id; //Mã giao dịch thanh toán tham chiếu của merchant
                $vnp_Amount = $order->total; // Số tiền thanh toán
                $vnp_Locale = 'vn'; //Ngôn ngữ chuyển hướng thanh toán
                $vnp_BankCode = 'VNBANK'; //Mã phương thức thanh toán
                $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán
                $startTime = date("YmdHis");
                $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));
    
                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => config('my-config.vnpay.tmn_code'),
                    "vnp_Amount" => $vnp_Amount * 23500,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
                    "vnp_OrderType" => "other",
                    "vnp_ReturnUrl" => route('call.back.vnpay'),
                    "vnp_TxnRef" => $vnp_TxnRef,
                    "vnp_ExpireDate" => $expire
                );
    
                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }
    
                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }
    
                $vnp_Url = config('my-config.vnpay.url') . "?" . $query;
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, config('my-config.vnpay.hash_secret'));
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            
                return Redirect::to($vnp_Url);
            }

            return redirect()->route('home')->with('msg', 'Dat hang thanh cong');
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->route('home')->with('msg', 'Dat hang that bai');
        }
    }
}
