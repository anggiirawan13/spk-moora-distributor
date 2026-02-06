<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriteriaSeeder extends Seeder
{
    public static function data(): array
    {
        return [
            [
                'code' => 'C1',
                'name' => 'Harga Produk',
                'weight' => 0.25,
                'attribute_type' => 'Cost',
            ],
            [
                'code' => 'C2',
                'name' => 'Waktu Kirim',
                'weight' => 0.20,
                'attribute_type' => 'Cost',
            ],
            [
                'code' => 'C3',
                'name' => 'Status Pajak',
                'weight' => 0.15,
                'attribute_type' => 'Benefit',
            ],
            [
                'code' => 'C4',
                'name' => 'Kualitas Produk',
                'weight' => 0.25,
                'attribute_type' => 'Benefit',
            ],
            [
                'code' => 'C5',
                'name' => 'Responsivitas Layanan',
                'weight' => 0.10,
                'attribute_type' => 'Benefit',
            ],
            [
                'code' => 'C6',
                'name' => 'Dukungan Purna Jual',
                'weight' => 0.05,
                'attribute_type' => 'Benefit',
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

        DB::table('criterias')->insert($rows);
    }
}
