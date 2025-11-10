<?php

namespace App\Modules\Ride\Models;

use Illuminate\Database\Eloquent\Model;

class RideStatus extends Model
{
    protected $fillable = ['ride_id', 'name'];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
