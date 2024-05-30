<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_balance',
        'account_number'
    ];

    public function user(){
        return $this->belongsTo(CustomUser::class, 'user_id');
    }
}
