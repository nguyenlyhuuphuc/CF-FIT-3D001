<?php

namespace App\Http\Requests;

use App\Events\OrderEvent;
use App\Models\Order;
use Illuminate\Http\Request;

class VNPaySerivce 
{
    public function callBackVNPay(Request $request){
        $order = Order::find($request->vnp_TxnRef);
        $orderPaymentMethod = $order->order_payment_methods[0];

        $responseCode = $request->vnp_ResponseCode;
        if($responseCode === '00'){
            event(new OrderEvent($order));

            $order->status = 'success';
            $order->save();

            $orderPaymentMethod->status = 'success';
            $orderPaymentMethod->save();

            return redirect()->route('home')->with('msg', 'Dat hang thanh cong');
        }else{
            $order->status = 'cancel';
            $order->save();

            $orderPaymentMethod->status = 'cancel';
            $orderPaymentMethod->save();

            return redirect()->route('home')->with('msg', 'Dat hang that bai');
        }
    }
}
