<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_categories')->insert([
            [
                'name' => 'Kabel Listrik',
                'description' => 'Berbagai jenis kabel listrik untuk instalasi elektrikal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Perlengkapan Penerangan',
                'description' => 'Lampu, fitting, dan aksesoris penerangan lainnya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Saklar dan Stop Kontak',
                'description' => 'Saklar, stop kontak, dan perlengkapan kontrol listrik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MCB dan Pengaman',
                'description' => 'Miniature Circuit Breaker dan perangkat pengaman listrik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Conduit dan Aksesoris',
                'description' => 'Pipa conduit, fitting, dan aksesoris instalasi listrik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Peralatan Tegangan Menengah',
                'description' => 'Perangkat untuk sistem distribusi tegangan menengah',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}