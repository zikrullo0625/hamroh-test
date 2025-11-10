<?php

namespace App\Modules\Driver\Repositories;

use App\Models\User;

class DriverRepository
{
    public array $ride_relations = ['status', 'driver', 'passenger', 'statuses', 'rating'];

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return User::drivers()->get();
    }

    public function getActualRide($user_id)
    {
        $user = User::findOrFail($user_id);

        return $user->ridesAsDriver()
            ->whereHas('status', function ($query) {
                $query->whereIn('name', ['in_progress', 'accepted', 'created']);
            })
            ->with($this->ride_relations)
            ->first();
    }

    public function getRides($id)
    {
        $user = User::findOrFail($id);

        return $user->ridesAsDriver()->with('driver')->get();
    }

    public function find($id)
    {
        return User::drivers()->findOrFail($id);
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
