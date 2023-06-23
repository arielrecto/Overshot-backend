<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
    public function image()
    {
        return $this->hasOne(ProductImage::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function sizes()
    {
        return $this->belongsToMany(Size::class)->withPivot('price');
    }
    public function addons(){
        return $this->belongsToMany(Addon::class);
    }
    public function levels () {
        return $this->belongsToMany(Level::class)->withPivot('percent');
    }
    public function customizes(){
        return $this->hasMany(Customize::class);
    }
}
