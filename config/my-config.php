<?php 
    return [
        'item_per_page' => env('PAGINATION_ITEM', 10),
        'name_test' => [
            'a' => [
                'b' => [
                    'c' => env('NAME_TEST', 'Le Van C')
                ]
            ]
        ],
        'vnpay' => [
            'tmn_code' => env('VNP_TMNCODE', 'PUEN5D41'),
            'hash_secret' => env('VNP_HASHSECRET', 'HOTFMHEKKTGITZXOWUHWZRDRHVSUEXXG'),
            'url' => env('VNP_URL',"https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"),
            'return_url' => env('VNP_RETURN_URL', "http://localhost/vnpay_php/vnpay_return.php"),
            'vnpay_api_url' => env('VNP_API_URL', "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html"),
            'api_url' => env('API_URL', "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction"),
        ],
    ];
    
?>