<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SneakerVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'sneaker_id', 'size', 'color', 'quantity'
    ];

    public function sneaker()
    {
        return $this->belongsTo(Sneaker::class);
    }
}
