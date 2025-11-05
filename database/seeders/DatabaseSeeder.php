<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            CriteriaSeeder::class,
            SubCriteriaSeeder::class,
            TransmissionTypeSeeder::class,
            FuelTypeSeeder::class,
            CarTypeSeeder::class,
            CarBrandSeeder::class,
            CarSeeder::class,
            AlternativeSeeder::class,
            BookingSeeder::class
        ]);
    }
}
