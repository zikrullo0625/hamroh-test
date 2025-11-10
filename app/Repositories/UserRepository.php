<?php

namespace App\Repositories;

use App\Models\User;
use DomainException;

class UserRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return User::passengers()->get();
    }

    public function userRides($id)
    {
        $user = User::findOrFail($id);
        return $user->rides()->with('driver')->get();
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
