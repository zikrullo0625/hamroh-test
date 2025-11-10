<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
        $this->call(VehicleSeeder::class);
        $this->call(DriverSeeder::class);
        $this->call(PassengerSeeder::class);
        $this->call(RideSeeder::class);
        $this->call(RideStatusSeeder::class);
    }
}
