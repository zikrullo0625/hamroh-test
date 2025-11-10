<?php

use App\Events\RideEvent;
use App\Modules\Ride\Models\Ride;
use Illuminate\Support\Facades\Route;

Route::get('/{all}', function () {
    return view('app');
})->where('all', '.*');
