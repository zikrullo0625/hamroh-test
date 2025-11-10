<?php

namespace Database\Seeders;

use App\Modules\Driver\Models\Vehicle;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ru_RU');

        $vehicle_count = 10;

        for ($i = 1; $i <= $vehicle_count; $i++) {
            $carNumber = $faker->randomNumber(4, true)
                . strtoupper($faker->randomLetter())
                . strtoupper($faker->randomLetter())
                . $faker->randomNumber(2, true);

            Vehicle::create([
                'brand' => $faker->randomElement(['BMW', 'Byd', 'Opel', 'Mercedes', 'Bugatti']),
                'model' => $faker->randomElement(['i5', 'x4', 'e2', 'e220', 'astra G']),
                'number' => $carNumber,
                'year' => $faker->year(),
                'color' => $faker->colorName(),
            ]);
        }
    }
}
