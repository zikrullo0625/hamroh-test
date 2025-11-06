<?php

use App\Modules\Driver\Controllers\DriverController;
use Illuminate\Support\Facades\Route;

Route::apiResource('drivers', DriverController::class);
