<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sneaker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'sneakers';

    protected $fillable = [
        'name', 'description', 'price', 'brand', 'discount'
    ];

    public function variants()
    {
        return $this->hasMany(SneakerVariant::class);
    }
}
