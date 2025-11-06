<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DistributorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('distributors')->insert([
                'name' => $faker->company() . ' Distributor',
                'image_name' => null,
                'company_name' => $faker->company(),
                'address' => $faker->address(),
                'phone' => $faker->phoneNumber(),
                'email' => $faker->companyEmail(),
                'payment_term_id' => $faker->numberBetween(1, 5),
                'delivery_method_id' => $faker->numberBetween(1, 4),
                'business_scale_id' => $faker->numberBetween(1, 5),
                'description' => $faker->optional()->paragraph(),
                'is_active' => $faker->boolean(80), // 80% chance of being active
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}