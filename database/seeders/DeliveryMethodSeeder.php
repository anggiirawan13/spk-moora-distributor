<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryMethodSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('delivery_methods')->insert([
            [
                'name' => 'Pengiriman Ekspres (1-2 hari)',
                'description' => 'Pengiriman cepat dengan estimasi 1-2 hari kerja',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pengiriman Reguler (3-5 hari)',
                'description' => 'Pengiriman standar dengan estimasi 3-5 hari kerja',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pickup Mandiri',
                'description' => 'Pelanggan mengambil barang langsung ke gudang distributor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pengiriman Gudang ke Gudang',
                'description' => 'Pengiriman antar gudang untuk order dalam jumlah besar',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}