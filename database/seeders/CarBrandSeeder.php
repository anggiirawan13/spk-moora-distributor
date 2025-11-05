<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarBrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('car_brands')->insert([
            [
                'name' => 'Toyota',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Honda',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
