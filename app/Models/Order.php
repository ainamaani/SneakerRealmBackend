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
        'total_amount',
        'status',
        'delivery_address',
        'payment_status',
        'order_date',
        'delivery_date'
    ];

    
    protected $casts = [
        'status' => OrderStatus::class,
        'payment_status' => PaymentMethod::class,
    ];
    

    public function user(){
        return $this->belongsTo(CustomUser::class);
    }

    public function sneaker(){
        return $this->hasMany(Sneaker::class);
    }
}
