<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Jobs\OrderSendMailJob;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMail
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

        OrderSendMailJob::dispatch($order, 'admin')->delay(Carbon::now()->addSeconds(5));
        
        OrderSendMailJob::dispatch($order, 'client')->delay(Carbon::now()->addSeconds(10));
    }
}
