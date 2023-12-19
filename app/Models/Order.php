<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function order_payment_methods(){
        return $this->hasMany(OrderPaymentMethod::class, 'order_id');
    }
}
