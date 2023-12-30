<?php

namespace App\Jobs;

use App\Mail\OrderAdminEmail;
use App\Mail\OrderClientEmail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OrderSendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;

    public string $type;
    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, string $type)
    {
        $this->order = $order;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = $this->order;
        $type = $this->type;

        if($type === 'admin'){
            Mail::to('nguyenlyhuuphuc@gmail.com')->send(new OrderAdminEmail($order));    
        }else{
            Mail::to('nguyenlyhuuphuc@gmail.com')->send(new OrderClientEmail($order));
        }
    }
}
