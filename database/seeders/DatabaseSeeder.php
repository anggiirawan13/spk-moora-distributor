<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CriteriaSeeder::class,
            SubCriteriaSeeder::class,
            BusinessScaleSeeder::class,
            DeliveryMethodSeeder::class,
            ProductSeeder::class,
            PaymentTermSeeder::class,
            DistributorSeeder::class,
            DistributorProductSeeder::class,
            AlternativeSeeder::class,
        ]);
    }
}
