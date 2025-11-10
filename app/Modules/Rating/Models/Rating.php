<?php

namespace App\Modules\Rating\Models;

use App\Models\User;
use App\Modules\Ride\Models\Ride;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'ride_id',
        'user_id',
        'stars',
        'comment'
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
