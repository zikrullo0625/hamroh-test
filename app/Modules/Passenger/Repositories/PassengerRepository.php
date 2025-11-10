<?php

namespace App\Modules\Passenger\Repositories;

use App\Models\User;

class PassengerRepository
{
    public array $ride_relations = ['status', 'driver', 'passenger', 'statuses', 'rating'];

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return User::passengers()->get();
    }

    public function getRides($id)
    {
        $user = User::findOrFail($id);

        return $user->ridesAsPassenger()->with('driver')->get();
    }

    public function getActualRide($user_id)
    {
        $user = User::findOrFail($user_id);

        return $user->ridesAsPassenger()
            ->whereHas('statuses', function ($query) {
                $query->whereIn('name', ['in_progress', 'accepted', 'created']);
            })
            ->with($this->ride_relations)
            ->first();
    }

    public function find($id)
    {
        return User::passengers()->findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
