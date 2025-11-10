<?php

use App\Http\Controllers\AuthController;
use App\Modules\Driver\Controllers\DriverController;
use App\Modules\Passenger\Controllers\PassengerController;
use App\Modules\Ride\Controllers\RideController;
use Illuminate\Support\Facades\Route;

Route::post('send-code', [AuthController::class, 'sendCode']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('drivers', DriverController::class);
    Route::apiResource('passengers', PassengerController::class);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('rides', RideController::class);
    Route::prefix('rides')->group(function () {
        Route::post('calculate-ride', [RideController::class, 'calculateRide']);
        Route::post('change-status/{id}', [RideController::class, 'changeStatus']);
        Route::post('/rate/{id}', [RideController::class, 'rate']);
    });
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/driver-actual-ride', [DriverController::class, 'actualRide']);
    Route::get('/passenger-actual-ride', [PassengerController::class, 'actualRide']);
    Route::post('/take-ride/{id}', [RideController::class, 'takeRide']);
});
