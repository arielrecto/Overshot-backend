<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'age',
        'gender',
        'street_no',
        'village',
        'municipality',
        'region',
        'zip_code',
        'tel_no',
        'phone_no',
        'user_id'
    ];

    public function user()
    {
       return $this->belongsTo(User::class);
    }
    public function avatar(){
        return $this->hasOne(Avatar::class);
    }
}
