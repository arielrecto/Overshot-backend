<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'user_id',
        'order_id',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function supplies()
    {
        return $this->belongsToMany(Supply::class);
    }
}
