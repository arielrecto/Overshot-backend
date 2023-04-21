<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_url',
        'profile_id'
    ];


    public function Profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
