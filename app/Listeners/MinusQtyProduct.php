<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MinusQtyProduct
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderEvent $event): void
    {
        $order = $event->order;
        foreach($order->order_items as $item){
            $product = Product::find($item->product_id);

            $qty = ($product->qty - $item->qty) < 0 ? 0 : ($product->qty - $item->qty);

            $product->qty = $qty;
            
            $product->save();
        }
    }
}
