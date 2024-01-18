<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;


    protected $fillable = [
        'ref',
        'quantity',
        'total',
        'is_check_out',
        'user_id'
    ];


    public function order(){
        return $this->hasOne(Order::class);
    }
    public function cartProducts(){
        return $this->hasMany(CartProduct::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
