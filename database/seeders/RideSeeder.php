<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Passenger\Controllers\PassengerController;
use App\Modules\Ride\Models\Ride;
use App\Modules\Ride\Models\RideStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ru_RU');

        $rides_count = 10;

        for ($i = 1; $i <= $rides_count; $i++) {
            Ride::create([
                'driver_id' => User::where('role', 'driver')->inRandomOrder()->first()->id,
                'passenger_id' => User::where('role', 'passenger')->inRandomOrder()->first()->id,
                'distance' => $faker->randomFloat(2,0,100),
                'price' => $faker->randomNumber(2, true),
                'from' => [$faker->randomFloat(4, 0, 100), $faker->randomFloat(4, 0, 100)],
                'to' => [$faker->randomFloat(4, 0, 100), $faker->randomFloat(4, 0, 100)],
                'from_address' => $faker->buildingNumber . ' ' . $faker->streetName,
                'to_address'   => $faker->buildingNumber . ' ' . $faker->streetName,
            ]);
        }
    }

}
