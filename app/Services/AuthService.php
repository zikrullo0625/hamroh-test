<?php

namespace App\Services;

use App\Models\User;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function login($number, $password)
    {
        $user = User::where('number', $number)->first();

        if ($user && Hash::check($password, $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'token' => $token,
                'user' => $user
            ];
        }

        throw new DomainException("Invalid credentials.");
    }

    public function updateToken($user): \Illuminate\Http\JsonResponse
    {
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $token;
    }

    public function register($number, $role, $name, $password, $code, $lat, $lng)
    {
        $cached_code = Cache::get($number);

        if ($code == $cached_code) {
            Log::info('Registering user with number: ' . $number);

            $user = User::create([
                'number' => $number,
                'role' => $role,
                'name' => $name,
                'password' => Hash::make($password)
            ]);

            if ($role === 'driver') {
                $user->location()->create([
                    'lat' => $lat,
                    'lng' => $lng
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'token' => $token,
                'user' => $user
            ];
        }

        throw new DomainException("Invalid sms code.");
    }
}
