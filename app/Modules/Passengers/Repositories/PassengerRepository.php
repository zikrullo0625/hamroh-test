<?php

namespace App\Modules\Passengers\Repositories;

use App\Models\User;

class PassengerRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return User::passengers()->get();
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
