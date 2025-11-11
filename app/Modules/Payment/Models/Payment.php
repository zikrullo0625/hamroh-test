<?php

namespace App\Modules\Payment\Models;

use App\Modules\Ride\Models\Ride;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['ride_id', 'type'];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
