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
                'name' => 'Distributor Nasional',
                'description' => 'Distributor dengan jangkauan seluruh Indonesia',
            ],
            [
                'name' => 'Distributor Regional',
                'description' => 'Distributor dengan jangkauan beberapa provinsi',
            ],
            [
                'name' => 'Distributor Lokal',
                'description' => 'Distributor dengan jangkauan dalam satu kota/kabupaten',
            ],
            [
                'name' => 'Supplier Per Kota',
                'description' => 'Supplier yang melayani area tertentu dalam kota',
            ],
            [
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
