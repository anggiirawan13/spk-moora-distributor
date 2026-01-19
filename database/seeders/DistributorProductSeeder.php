<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistributorProductSeeder extends Seeder
{
    public function run(): void
    {
        $distributorProductMapping = [
            1 => [1, 3, 4, 5, 6, 10, 14, 15],
            2 => [1, 7, 9, 11, 13, 15],
            3 => [1, 2, 3, 12, 13, 14],
            4 => [2, 3, 7, 11, 14, 15],
            5 => [3, 4, 5, 6, 14],
            6 => [9, 10, 11],
            7 => [1, 3, 6, 12, 15],
            8 => [1, 5, 6],
            9 => [2, 7, 11, 13, 15],
            10 => [1, 5, 8, 14, 15],
            11 => [3, 5, 8, 9, 10, 11],
            12 => [2, 14],
            13 => [1, 3, 4, 5, 6, 14],
            14 => [1, 3, 8, 12, 13, 15],
            15 => [2, 3, 4, 11, 14],
            16 => [3, 8, 9, 10],
            17 => [1, 3, 5, 8, 14],
            18 => [1, 3, 4, 8, 14, 15],
            19 => [1, 2, 13, 15],
            20 => [1, 3, 5, 14, 15],
        ];

        $dataToInsert = [];
        foreach ($distributorProductMapping as $distributorId => $productIds) {
            foreach ($productIds as $productId) {
                $dataToInsert[] = [
                    'distributor_id' => $distributorId,
                    'product_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => 1,
                    'updated_by' => 1,
                ];
            }
        }

        DB::table('distributor_product')->insert($dataToInsert);
    }
}