<?php

namespace App\Modules\Driver\Repositories;

use App\Models\User;

class DriverRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return User::drivers()->get();
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
