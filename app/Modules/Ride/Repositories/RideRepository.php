<?php

namespace App\Modules\Ride\Repositories;

use App\Modules\Driver\Repositories\DriverRepository;
use App\Modules\Ride\DTO\RideDTO;
use App\Modules\Ride\Models\Ride;

class RideRepository
{
    public function __construct(public DriverRepository $driverRepository)
    {

    }
    public array $relations = [
        'driver.vehicle',
        'passenger',
        'statuses',
        'status',
        'rating',
        'payment'
    ];

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Ride::with($this->relations)->get();
    }

    public function rate($id, $comment, $rating, $user_id)
    {
        $ride = Ride::find($id);
        return  $ride->rating()->create([
            'comment' => $comment,
            'stars' => $rating,
            'user_id' => $user_id
        ]);
    }

    public function latest(): \Illuminate\Database\Eloquent\Collection
    {
        return Ride::with($this->relations)->orderBy('created_at', 'desc')->get();
    }

    public function find($id)
    {
        return Ride::with($this->relations)->findOrFail($id);
    }

    public function create(array $data)
    {
        $ride = Ride::create($data);
        $ride->setStatus('created');
        $this->createPayment($ride, $data['payment_type']);
        return $ride->load($this->relations);
    }

    public function createPayment(Ride $ride, $payment_type)
    {
        $ride->payment()->create([
            'type' => $payment_type,
        ]);
        $ride->save();
    }

    public function update(Ride $ride, array $data): Ride
    {
        $ride->update($data);
        return $ride->load($this->relations);
    }

    public function delete(Ride $ride): ?bool
    {
        return $ride->delete();
    }

    public function changeStatus($id, string $status)
    {
        $ride = $this->find($id);
        return $ride->setStatus($status);
    }

    public function assignDriverToRide($ride_id, $driver_id)
    {
        $ride = $this->find($ride_id);
        $ride->driver_id = $driver_id;
        $this->changeStatus($ride_id, 'accepted');
        $ride->save();

        return $ride->load($this->relations);
    }
}
