<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Faker\Factory as Faker;

class PassengerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ru_RU');

        $passengers_count = 10;

        for ($i = 1; $i <= $passengers_count; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'number' => $faker->unique()->numerify('##########'),
                'email_verified_at' => now(),
                'password' => Hash::make('P@ssword123'),
                'role' => 'passenger',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
