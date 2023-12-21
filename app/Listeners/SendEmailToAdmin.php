<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Mail\OrderAdminEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToAdmin
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

        Mail::to('nguyenlyhuuphuc@gmail.com')->send(new OrderAdminEmail($order));
    }
}
