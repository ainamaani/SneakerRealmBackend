<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'delivery_address',
        'payment_method',
        'order_date',
        'delivery_date',
        'total_price'
    ];

    
    protected $casts = [
        'status' => OrderStatus::class,
    ];
    

    public function user(){
        return $this->belongsTo(CustomUser::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }
}
