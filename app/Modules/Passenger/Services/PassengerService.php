<?php

namespace App\Modules\Passenger\Services;

use App\Modules\Passenger\DTO\PassengerDTO;
use App\Modules\Passenger\Repositories\PassengerRepository;
use Illuminate\Support\Facades\Hash;

class PassengerService
{
    protected PassengerRepository $passengerRepository;

    public function __construct(PassengerRepository $passengerRepository)
    {
        $this->passengerRepository = $passengerRepository;
    }

    public function index()
    {
        return $this->passengerRepository->all();
    }

    public function show($id)
    {
        return $this->passengerRepository->find($id);
    }

    public function store(PassengerDTO $data)
    {
        return $this->passengerRepository->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
    }

    public function update($id, array $data)
    {
        $user = $this->passengerRepository->find($id);

        $data['password'] = Hash::make($data['password']);

        return $this->passengerRepository->update($user, $data);
    }

    public function destroy($id)
    {
        $user = $this->passengerRepository->find($id);
        return $this->passengerRepository->delete($user);
    }

    public function actualRide($user_id)
    {
        return $this->passengerRepository->getActualRide($user_id);
    }
}
