<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_num',
        'quantity',
        'total',
        'user_id',
        'type',
        'status',
        'location_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'size');
    }
    public function transaction(){
        return $this->hasOne(Transaction::class);
    }
    public function payment () {
        return $this->hasOne(Payment::class);
    }
    public function location(){
        return $this->belongsTo(Location::class);
    }
    public function ratings(){
        return $this->hasMany(Rating::class);
    }
}
