<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Kabel NYY 4x16 mm²',
                'description' => 'Kabel tanah NYY 4 inti 16 mm² untuk instalasi bawah tanah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lampu LED Panel 60x60',
                'description' => 'Lampu LED panel 60x60 cm untuk penerangan ruangan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Saklar Tunggal Putih',
                'description' => 'Saklar tunggal warna putih merek Broco',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MCB 2 Pole 16A',
                'description' => 'Miniature Circuit Breaker 2 pole 16 ampere',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pipa PVC 3/4"',
                'description' => 'Pipa PVC diameter 3/4 inch untuk instalasi listrik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stop Kontak Ganda',
                'description' => 'Stop kontak ganda dengan grounding',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}