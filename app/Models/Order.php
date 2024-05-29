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
        'sneaker_id',
        'sneaker_variant_id',
        'quantity',
        'unit_price',
        'quantity_price',
        'status',
        'delivery_address',
        'payment_method',
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
