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
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'size');
    }
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    public function payment () {
        return $this->hasOne(Payment::class);
    }
}
