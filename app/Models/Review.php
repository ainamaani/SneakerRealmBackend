<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sneaker_id',
        'rating',
        'review'
    ];

    public function user(){
        return $this->belongsTo(CustomUser::class);
    }

    public function sneaker(){
        return $this->belongsTo(Sneaker::class);
    }
}
