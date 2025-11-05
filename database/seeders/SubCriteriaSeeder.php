<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $subCriteria = [
            // C1 - Harga Produk (Cost)
            ['criteria_code' => 'C1', 'name' => 'Sangat Mahal (> 20% di atas rata-rata)', 'value' => 1],
            ['criteria_code' => 'C1', 'name' => 'Mahal (10-20% di atas rata-rata)', 'value' => 2],
            ['criteria_code' => 'C1', 'name' => 'Standar (sesuai harga pasar)', 'value' => 3],
            ['criteria_code' => 'C1', 'name' => 'Kompetitif (5-10% di bawah rata-rata)', 'value' => 4],
            ['criteria_code' => 'C1', 'name' => 'Sangat Kompetitif (> 10% di bawah rata-rata)', 'value' => 5],

            // C2 - Kualitas Produk (Benefit)
            ['criteria_code' => 'C2', 'name' => 'Kualitas rendah (banyak komplain)', 'value' => 1],
            ['criteria_code' => 'C2', 'name' => 'Kualitas standar (sesuai spesifikasi)', 'value' => 2],
            ['criteria_code' => 'C2', 'name' => 'Kualitas baik (jarang komplain)', 'value' => 3],
            ['criteria_code' => 'C2', 'name' => 'Kualitas sangat baik (produk premium)', 'value' => 4],
            ['criteria_code' => 'C2', 'name' => 'Kualitas terbaik (bersertifikat internasional)', 'value' => 5],

            // C3 - Ketepatan Pengiriman (Benefit)
            ['criteria_code' => 'C3', 'name' => 'Sering terlambat (> 30% order)', 'value' => 1],
            ['criteria_code' => 'C3', 'name' => 'Kadang terlambat (15-30% order)', 'value' => 2],
            ['criteria_code' => 'C3', 'name' => 'Tepat waktu (85-95% order)', 'value' => 3],
            ['criteria_code' => 'C3', 'name' => 'Sangat tepat waktu (95-98% order)', 'value' => 4],
            ['criteria_code' => 'C3', 'name' => 'Selalu tepat waktu (> 98% order)', 'value' => 5],

            // C4 - Layanan Purna Jual (Benefit)
            ['criteria_code' => 'C4', 'name' => 'Tidak ada layanan purna jual', 'value' => 1],
            ['criteria_code' => 'C4', 'name' => 'Layanan terbatas (garansi dasar)', 'value' => 2],
            ['criteria_code' => 'C4', 'name' => 'Layanan standar (garansi + support teknis)', 'value' => 3],
            ['criteria_code' => 'C4', 'name' => 'Layanan baik (garansi panjang + training)', 'value' => 4],
            ['criteria_code' => 'C4', 'name' => 'Layanan excellent (24/7 support + maintenance)', 'value' => 5],

            // C5 - Fleksibilitas Pembayaran (Benefit)
            ['criteria_code' => 'C5', 'name' => 'Cash only', 'value' => 1],
            ['criteria_code' => 'C5', 'name' => 'Cash + COD', 'value' => 2],
            ['criteria_code' => 'C5', 'name' => 'Cash + tempo 7 hari', 'value' => 3],
            ['criteria_code' => 'C5', 'name' => 'Cash + tempo 30 hari', 'value' => 4],
            ['criteria_code' => 'C5', 'name' => 'Multiple options (cash, tempo, cicil)', 'value' => 5],

            // C6 - Jangkauan Distribusi (Benefit)
            ['criteria_code' => 'C6', 'name' => 'Lokal (satu kota)', 'value' => 1],
            ['criteria_code' => 'C6', 'name' => 'Regional (beberapa kota)', 'value' => 2],
            ['criteria_code' => 'C6', 'name' => 'Provinsi', 'value' => 3],
            ['criteria_code' => 'C6', 'name' => 'Nasional (pulau Jawa)', 'value' => 4],
            ['criteria_code' => 'C6', 'name' => 'Nasional (seluruh Indonesia)', 'value' => 5],
        ];

        foreach ($subCriteria as $item) {
            $criteria = DB::table('criterias')->where('code', $item['criteria_code'])->first();

            if ($criteria) {
                DB::table('sub_criterias')->insert([
                    'criteria_id' => $criteria->id,
                    'name' => $item['name'],
                    'value' => $item['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}