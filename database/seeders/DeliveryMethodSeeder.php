<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryMethodSeeder extends Seeder
{
    public static function data(): array
    {
        return [
            [
                'code' => 'MP001',
                'name' => 'Pengiriman Ekspres (1-2 hari)',
                'description' => 'Pengiriman cepat dengan estimasi 1-2 hari kerja',
            ],
            [
                'code' => 'MP002',
                'name' => 'Pengiriman Reguler (3-5 hari)',
                'description' => 'Pengiriman standar dengan estimasi 3-5 hari kerja',
            ],
            [
                'code' => 'MP003',
                'name' => 'Pickup Mandiri',
                'description' => 'Pelanggan mengambil barang langsung ke gudang distributor',
            ],
            [
                'code' => 'MP004',
                'name' => 'Pengiriman Gudang ke Gudang',
                'description' => 'Pengiriman antar gudang untuk order dalam jumlah besar',
            ],
        ];
    }

    public function run(): void
    {
        $rows = [];
        foreach (self::data() as $row) {
            $rows[] = $row + [
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ];
        }

        DB::table('delivery_methods')->insert($rows);
    }
}
