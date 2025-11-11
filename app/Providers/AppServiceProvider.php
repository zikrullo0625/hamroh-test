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


        Gate::define('view-ride', function ($user, Ride $ride) {
            if ($ride->status->name === 'created') {
                if ($user->role === 'passenger' && $ride->passenger_id !== $user->id) {
                    return false;
                }
                return true;
            }

            if ($user->role === 'driver' && $ride->driver_id === $user->id) {
                return true;
            }

            if ($user->role === 'passenger' && $ride->passenger_id === $user->id) {
                return true;
            }

            return false;
        });

        Gate::define('cancel-ride', function ($user, Ride $ride) {
            if ($ride->status->name !== 'created') return false;
            return ($user->role === 'driver' && $ride->driver_id === $user->id) ||
                ($user->role === 'passenger' && $ride->passenger_id === $user->id);
        });

        Gate::define('change-ride-status', function ($user, Ride $ride) {
            if ($user->role === 'driver' && $ride->driver_id === $user->id) return true;
            return false;
        });

        Gate::define('create-ride', function ($user) {
            $hasActiveRide = Ride::where($user->role . '_id', $user->id)
                ->whereHas('status', function ($q) {
                    $q->whereIn('name', ['in_progress', 'accepted', 'created']);
                })
                ->exists();

            return !$hasActiveRide;
        });
    }
}
