<?php

namespace App\Modules\Passengers\Services;

use App\Modules\Passengers\DTO\PassengerDTO;
use App\Modules\Passengers\Repositories\PassengerRepository;
use Illuminate\Support\Facades\Hash;

class PassengerService
{
    protected PassengerRepository $userRepository;

    public function __construct(PassengerRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return $this->userRepository->all();
    }

    public function show($id)
    {
        return $this->userRepository->find($id);
    }

    public function store(PassengerDTO $data)
    {
        return $this->userRepository->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
    }

    public function update($id, array $data)
    {
        $user = $this->userRepository->find($id);

        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->update($user, $data);
    }

    public function destroy($id)
    {
        $user = $this->userRepository->find($id);
        return $this->userRepository->delete($user);
    }
}
