<?php

namespace App\Modules\Ride\Services;

use App\Events\RideEvent;
use App\Models\User;
use App\Modules\Ride\DTO\RideDTO;
use App\Modules\Ride\Repositories\RideRepository;
use App\Modules\Driver\Repositories\DriverRepository;
use App\Modules\Passenger\Repositories\PassengerRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RideService
{
    public function __construct(
        protected RideRepository $rideRepository,
        protected DriverRepository $driverRepository,
        protected PassengerRepository $passengerRepository,
        protected UserRepository $userRepository
    )
    {}

    public function index()
    {
        return $this->rideRepository->latest();
    }

    public function show($id)
    {
        $ride = $this->rideRepository->find($id);

        if (Gate::denies('view-ride', $ride)) {
            abort(403, 'У вас нету доступа!');
        }

        return $ride;
    }

    public function store(RideDTO $data)
    {
        if (Gate::denies('create-ride')) {
            abort(403, 'У вас нету доступа!');
        }

        $ride = $this->rideRepository->create($data->toArray());

        $data = $ride->toArray();
        $data['_'] = 'newRide';
        broadcast(new RideEvent($data));

        return $ride;
    }

    public function update($id, array $data)
    {
        $ride = $this->rideRepository->find($id);

        $data['password'] = Hash::make($data['password']);

        return $this->rideRepository->update($ride, $data);
    }

    public function destroy($id)
    {
        $ride = $this->rideRepository->find($id);
        return $this->rideRepository->delete($ride);
    }

    public function calculateRide(array $start, array $end)
    {
        $url = "https://router.project-osrm.org/route/v1/driving/{$start[0]},{$start[1]};{$end[0]},{$end[1]}?overview=full&geometries=geojson";

        $response = Http::get($url);
        $data = $response->json();

        if (!isset($data['routes'][0])) {
            throw new \DomainException('Не удалось построить маршрут');
        }

        $route = $data['routes'][0];

        $distanceKm = round($route['distance'] / 1000, 2);
        $durationMin = round($route['duration'] / 60);
        $estimatedCost = $this->calculateCost($distanceKm);

        return [
            'distance_km' => $distanceKm,
            'duration_min' => $durationMin,
            'estimated_cost' => $estimatedCost,
            'from_address' => $data['waypoints'][0]['name'] ?? null,
            'to_address' => $data['waypoints'][1]['name'] ?? null,
            'geometry' => array_map(function ($coord) {
                return [$coord[1], $coord[0]];
            }, $route['geometry']['coordinates'])];
    }

    public function calculateCost($distanceKm): float
    {
        $baseFare = 3;
        $pricePerKm = 2;

        return ceil($baseFare + ($distanceKm * $pricePerKm));
    }

    public function changeStatus($id, string $status)
    {
        $ride = $this->rideRepository->find($id);

        if ($status === 'cancelled') {
            if (Gate::denies('cancel-ride', $ride)) {
                abort(403, 'У вас нету доступа!');
            }

            $rideArray = $ride->toArray();
            $rideArray['_'] = 'deleteRide';
            broadcast(new RideEvent($rideArray));

            return $this->rideRepository->changeStatus($id, $status);
        }

        if (Gate::denies('change-ride-status', $ride)) {
            abort(403, 'У вас нету доступа!.');
        }

        return $this->rideRepository->changeStatus($id, $status);
    }

    public function rate($id, $comment, $rating, $user_id)
    {
        return $this->rideRepository->rate($id, $comment, $rating, $user_id);
    }

    public function takeRide($ride_id, $driver_id)
    {
        $ride = $this->rideRepository->assignDriverToRide($ride_id, $driver_id);

        $data = $ride->toArray();
        $data['_'] = 'deleteRide';
        broadcast(new RideEvent($data));

        return $ride;
    }
}
