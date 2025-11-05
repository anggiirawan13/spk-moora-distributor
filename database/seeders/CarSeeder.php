<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            DB::table('cars')->insert([
                'name' => $faker->unique()->word() . ' ' . $faker->unique()->numberBetween(100, 999),
                'image_name' => null,
                'price' => $faker->numberBetween(100000000, 500000000),
                'manufacture_year' => $faker->year(),
                'brand_id' => $faker->numberBetween(1, 2),
                'mileage' => $faker->numberBetween(1000, 100000),
                'fuel_type_id' => $faker->numberBetween(1, 2),
                'engine_capacity' => $faker->numberBetween(1000, 3000),
                'car_type_id' => $faker->numberBetween(1, 3),
                'seat_count' => $faker->numberBetween(2, 7),
                'transmission_type_id' => $faker->numberBetween(1, 2),
                'color' => $faker->safeColorName(),
                'description' => $faker->optional()->paragraph(),
                'is_available' => $faker->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

