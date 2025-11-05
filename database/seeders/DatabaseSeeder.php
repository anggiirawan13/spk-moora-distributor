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
            BusinessScaleSeeder::class,
            DeliveryMethodSeeder::class,
            ProductCategorySeeder::class,
            PaymentTermSeeder::class,
            DistributorSeeder::class,
            AlternativeSeeder::class,
        ]);
    }
}