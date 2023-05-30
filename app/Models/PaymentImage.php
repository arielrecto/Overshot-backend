<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'payment_id'
    ];

    public function payment() {
        return $this->belongsTo(Payment::class);
    }
}



