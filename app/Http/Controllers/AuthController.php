<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function __construct(
        public AuthService  $authService,
        public SmsService  $smsService,
    )
    {}

    public function login(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|integer',
            'password' => 'required|string'
        ]);

        try {
            $data = $this->authService->login($validated['number'], $validated['password']);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'error' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'token' => $data['token'],
            'user' => $data['user']
        ]);
    }

    public function sendCode(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|integer',
        ]);

        try {
            $this->smsService->sendCode($validated['number']);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'error' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'code' => Cache::get($validated['number'])
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|integer',
            'name' => 'required|string',
            'role' => 'required|string',
            'password' => 'required|string',
            'code' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        try {
            $data = $this->authService->register(
                $validated['number'],
                $validated['role'],
                $validated['name'],
                $validated['password'],
                $validated['code'],
                $validated['lat'],
                $validated['lng'],
            );
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'error' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'token' => $data['token'],
            'user' => $data['user']
        ]);
    }
}
