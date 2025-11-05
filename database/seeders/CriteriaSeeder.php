<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('criterias')->insert([
            [
                'code' => 'C1',
                'name' => 'Harga Produk',
                'weight' => 0.25,
                'attribute_type' => 'Cost',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'C2',
                'name' => 'Kualitas Produk',
                'weight' => 0.25,
                'attribute_type' => 'Benefit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'C3',
                'name' => 'Ketepatan Pengiriman',
                'weight' => 0.20,
                'attribute_type' => 'Benefit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'C4',
                'name' => 'Layanan Purna Jual',
                'weight' => 0.15,
                'attribute_type' => 'Benefit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'C5',
                'name' => 'Fleksibilitas Pembayaran',
                'weight' => 0.10,
                'attribute_type' => 'Benefit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'C6',
                'name' => 'Jangkauan Distribusi',
                'weight' => 0.05,
                'attribute_type' => 'Benefit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}