<?php

namespace Database\Seeders;

use App\Modules\Ride\Models\Ride;
use App\Modules\Ride\Models\RideStatus;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RideStatusSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ru_RU');
        $rides = Ride::all();

        foreach ($rides as $ride) {
            $statuses = ['created', 'accepted', 'in_progress'];

            foreach ($statuses as $status) {
                RideStatus::create([
                    'ride_id' => $ride->id,
                    'name' => $status,
                    'created_at' => $faker->dateTimeBetween('-2 days', 'now'),
                ]);
            }
        }
    }
}
