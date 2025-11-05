<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('car_types')->insert([
            [
                'name' => 'LCGC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MPV',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SUV',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
