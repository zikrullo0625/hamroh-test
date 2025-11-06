<?php

use App\Modules\Driver\Controllers\DriverController;
use App\Modules\Passengers\Controllers\PassengerController;
use Illuminate\Support\Facades\Route;

Route::apiResource('drivers', DriverController::class);
Route::apiResource('passengers', PassengerController::class);
