<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistributorProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('distributor_product')->insert([
            // Distributor 1 memiliki produk 1, 2, 3
            [
                'distributor_id' => 1,
                'product_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'distributor_id' => 1,
                'product_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'distributor_id' => 1,
                'product_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Distributor 2 memiliki produk 2, 3, 4
            [
                'distributor_id' => 2,
                'product_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'distributor_id' => 2,
                'product_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'distributor_id' => 2,
                'product_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Distributor 3 memiliki produk 1, 4, 5
            [
                'distributor_id' => 3,
                'product_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'distributor_id' => 3,
                'product_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'distributor_id' => 3,
                'product_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Distributor 4 memiliki produk 3, 5, 6
            [
                'distributor_id' => 4,
                'product_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'distributor_id' => 4,
                'product_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'distributor_id' => 4,
                'product_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Distributor 5 memiliki produk 1, 2, 6
            [
                'distributor_id' => 5,
                'product_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'distributor_id' => 5,
                'product_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'distributor_id' => 5,
                'product_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}