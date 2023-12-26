<?php

namespace App\Http\Service;

use App\Events\OrderEvent;
use App\Models\Order;
use Illuminate\Http\Request;

class VNPaySerivce 
{
    public function callBackVNPay(Request $request){
        $orderId = (int)$request->vnp_TxnRef - 2000;

        $order = Order::find($orderId);
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

    public function getVNPayUrl(Order $order,string $type): string {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $orderId = (int)$order->id + 2000;
        $vnp_TxnRef = (string)$orderId; //Mã giao dịch thanh toán tham chiếu của merchant
        $vnp_Amount = $order->total; // Số tiền thanh toán
        $vnp_Locale = 'vn'; //Ngôn ngữ chuyển hướng thanh toán
        $vnp_BankCode = $type; //Mã phương thức thanh toán
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

        return $vnp_Url;
    }
}
