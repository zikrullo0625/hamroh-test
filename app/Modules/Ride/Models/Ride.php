<?php

namespace App\Modules\Ride\Models;

use App\Models\User;
use App\Modules\Payment\Models\Payment;
use App\Modules\Rating\Models\Rating;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = [
        'driver_id',
        'passenger_id',
        'distance',
        'duration',
        'geometry',
        'price',
        'from',
        'to',
        'from_address',
        'to_address',
    ];

    protected $casts = [
        'from' => 'array',
        'to' => 'array',
        'geometry' => 'array',
    ];
    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function passenger(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function statuses()
    {
        return $this->hasMany(RideStatus::class);
    }

    public function status()
    {
        return $this->hasOne(RideStatus::class)->latestOfMany();
    }

    public function setStatus(string $status)
    {
        return $this->statuses()->create([
            'name' => $status,
        ]);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
