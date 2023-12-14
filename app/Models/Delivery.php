<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'location_id'
    ];

    public function User () {
        return $this->belongsTo(User::class);
    }
    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }
    public function location (){
        return $this->belongsTo(Location::class);
    }
}
