<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FuelTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fuel_types')->insert([
            [
                'name' => 'Bensin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Solar',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
