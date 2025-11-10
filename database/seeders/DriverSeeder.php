<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Driver\Models\Vehicle;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ru_RU');

        $drivers_count = 10;

        for ($i = 1; $i <= $drivers_count; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'number' => $faker->unique()->numerify('##########'),
                'email_verified_at' => now(),
                'password' => Hash::make('P@ssword123'),
                'role' => 'driver',
                'vehicle_id' => $faker->numberBetween(1, Vehicle::count()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
