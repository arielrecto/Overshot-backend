<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'quantity',
        'amount',
        'unit',
        'category',
        'expiry_date',
        'manufacturer',
        'price'
    ];

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class);
    }
}
