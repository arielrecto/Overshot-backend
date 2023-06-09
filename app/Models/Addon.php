<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function products() {
        return $this->belongsToMany(Product::class);
    }
    public function customizes() {
        return $this->belongsToMany(Customize::class);
    }
}
