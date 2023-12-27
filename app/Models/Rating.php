<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate',
        'message',
        'product_id',
    ];


    public function product(){
        return $this->belongsTo(product::class);
    }
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
