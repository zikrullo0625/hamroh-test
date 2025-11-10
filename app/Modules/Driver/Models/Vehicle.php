<?php

namespace App\Modules\Driver\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'number',
        'year',
        'color',
    ];
}
