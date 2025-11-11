<?php

use App\Events\RideEvent;
use App\Modules\Ride\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(base_path('routes/api/v1.php'));

Route::get('/tws', function () {
    $data = Ride::find(29)->toArray();
    $data['_'] = 'newRide';
    broadcast(new RideEvent($data));
});

