<?php

namespace App\Modules\Driver\Services;

use App\Models\User;
use App\Modules\Driver\DTO\DriverDTO;
use App\Modules\Driver\Repositories\DriverRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class DriverService
{
    protected DriverRepository $driverRepository;

    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function index()
    {
        return $this->driverRepository->all();
    }

    public function show($id)
    {
        return $this->driverRepository->find($id);
    }

    public function store(DriverDTO $data)
    {
        return $this->driverRepository->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
    }

    public function update($id, array $data)
    {
        $user = $this->driverRepository->find($id);

        $data['password'] = Hash::make($data['password']);

        return $this->driverRepository->update($user, $data);
    }

    public function destroy($id)
    {
        $user = $this->driverRepository->find($id);
        return $this->driverRepository->delete($user);
    }

    public function actualRide($user_id)
    {
        $ride = $this->driverRepository->getActualRide($user_id);

        if ($ride && Gate::denies('view-ride', $ride)) {
            abort(403, 'У вас нету доступа!');
        }

        return $ride;
    }
}
