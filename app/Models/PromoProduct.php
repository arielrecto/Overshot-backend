<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'promo_id'
    ];


    public function promo(){
        return $this->BelongsTo(Promo::class);
    }

    public function product(){
        return $this->belongsTo(Product::class)->with(['image']);
    }
}
