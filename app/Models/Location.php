<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'address',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function delivery() {
        return $this->hasOne(Delivery::class);
    }
    public function order(){
        return $this->hasOne(Order::class);
    }
}
