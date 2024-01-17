<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySupply extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplies'
    ];
}
