<?php

namespace App\Providers;

use App\Models\User;
use App\Modules\Ride\Models\Ride;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('end-ride', function (User $user, Ride $ride) {
            return $user->id === $ride->driver_id;
        });

        Gate::define('see-ride', function (User $user, Ride $ride) {
            return $user->id === $ride->driver_id;
        });
    }
}
