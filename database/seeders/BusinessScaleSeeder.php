<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessScaleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('business_scales')->insert([
            [
                'name' => 'Distributor Nasional',
                'description' => 'Distributor dengan jangkauan seluruh Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Distributor Regional',
                'description' => 'Distributor dengan jangkauan beberapa provinsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Distributor Lokal',
                'description' => 'Distributor dengan jangkauan dalam satu kota/kabupaten',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supplier Per Kota',
                'description' => 'Supplier yang melayani area tertentu dalam kota',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reseller Terotorisasi',
                'description' => 'Reseller resmi dengan wilayah pemasaran terbatas',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}