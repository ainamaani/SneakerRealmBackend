<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;


class CustomUser extends Model implements JWTSubject
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'custom_users';

    protected $fillable = [
        'full_name', 
        'email', 
        'contact', 
        'address',
        'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function account(){
        return $this->hasOne(Account::class, 'user_id');
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }
}
