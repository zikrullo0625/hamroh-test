<?php

namespace App\Modules\Driver\Services;

use App\Modules\Driver\DTO\DriverDTO;
use App\Modules\Driver\Repositories\DriverRepository;
use Illuminate\Support\Facades\Hash;

class DriverService
{
    protected DriverRepository $userRepository;

    public function __construct(DriverRepository $userRepository)
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

    public function store(DriverDTO $data)
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
