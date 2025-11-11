<?php

namespace App\Console\Commands;

use App\Events\RideEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RideWorker extends Command
{
    protected $signature = 'ride:listen';
    protected $description = 'ride background worker';

    public function handle()
    {
        $this->info('Ride worker started');

        $attempts = 60; //5min

        while (true) {
            try {
                $cached_rides = Cache::get('rides', []);

                if (empty($cached_rides)) {
                    sleep(5);
                    continue;
                }

                $new_cache = [];

                foreach ($cached_rides as $ride) {
                    if (!isset($ride['_tries'])) {
                        $ride['_tries'] = 0;
                    }

                    if ($ride['_tries'] < $attempts) {
                        broadcast(new RideEvent($ride));
                        $ride['_tries']++;
                    }

                    if ($ride['_tries'] >= $attempts) {
                        if (($ride['_'] ?? '') === 'newRide') {
                            $deleteRide = $ride;
                            $deleteRide['_'] = 'deleteRide';
                            broadcast(new RideEvent($deleteRide));
                        }
                        continue;
                    }

                    $new_cache[] = $ride;
                }

                Cache::put('rides', $new_cache);
            } catch (\Throwable $e) {
                Log::error('Ошибка в воркере: ' . $e->getMessage());
            }

            sleep(5);
        }
    }
}
