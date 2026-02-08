<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessScaleSeeder extends Seeder
{
    public static function data(): array
    {
        return [
            [
                'code' => 'SB001',
                'name' => 'Distributor Nasional',
                'description' => 'Distributor dengan jangkauan seluruh Indonesia',
            ],
            [
                'code' => 'SB002',
                'name' => 'Distributor Regional',
                'description' => 'Distributor dengan jangkauan beberapa provinsi',
            ],
            [
                'code' => 'SB003',
                'name' => 'Distributor Lokal',
                'description' => 'Distributor dengan jangkauan dalam satu kota/kabupaten',
            ],
            [
                'code' => 'SB004',
                'name' => 'Supplier Per Kota',
                'description' => 'Supplier yang melayani area tertentu dalam kota',
            ],
            [
                'code' => 'SB005',
                'name' => 'Reseller Terotorisasi',
                'description' => 'Reseller resmi dengan wilayah pemasaran terbatas',
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

        DB::table('business_scales')->insert($rows);
    }
}
