<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'code',
        'percent',
        'decimal_value',
        'is_active'
    ];


    public function products(){
        return $this->hasMany(PromoProduct::class)->with(['product']);
    }
}
