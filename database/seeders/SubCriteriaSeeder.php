<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $subCriteria = [
            ['criteria_code' => 'C1', 'name' => '> 120% (Sangat Rendah)', 'value' => 1],
            ['criteria_code' => 'C1', 'name' => '105% - 120% (Rendah)', 'value' => 2],
            ['criteria_code' => 'C1', 'name' => '95% - 105% (Standar)', 'value' => 3],
            ['criteria_code' => 'C1', 'name' => '80% - 95% (Tinggi)', 'value' => 4],
            ['criteria_code' => 'C1', 'name' => '< 80% (Sangat Tinggi)', 'value' => 5],

            ['criteria_code' => 'C2', 'name' => '> 7 hari (Sangat Lambat)', 'value' => 1],
            ['criteria_code' => 'C2', 'name' => '5 – 7 hari (Lambat)', 'value' => 2],
            ['criteria_code' => 'C2', 'name' => '3 – 4 hari (Normal)', 'value' => 3],
            ['criteria_code' => 'C2', 'name' => '2 hari (Cepat)', 'value' => 4],
            ['criteria_code' => 'C2', 'name' => '1 hari (Sangat Cepat)', 'value' => 5],

            ['criteria_code' => 'C3', 'name' => 'PPN (Kurang Disukai)', 'value' => 1],
            ['criteria_code' => 'C3', 'name' => 'Non-PPN (Lebih Disukai)', 'value' => 2],

            ['criteria_code' => 'C4', 'name' => 'Produk non-brand, tanpa sertifikasi, klaim > 5% (Sangat Kurang)', 'value' => 1],
            ['criteria_code' => 'C4', 'name' => 'Produk kurang dikenal/lokal, klaim 3% - 5% (Kurang)', 'value' => 2],
            ['criteria_code' => 'C4', 'name' => 'Produk standar, klaim 1,5% - 3% (Cukup)', 'value' => 3],
            ['criteria_code' => 'C4', 'name' => 'Produk branded, sertifikasi umum, klaim 0,5% - 1,5% (Baik)', 'value' => 4],
            ['criteria_code' => 'C4', 'name' => 'Produk premium, sertifikasi lengkap, klaim < 0,5% (Sangat Baik)', 'value' => 5],

            ['criteria_code' => 'C5', 'name' => 'Respon sangat lambat (> 1 hari), perlu follow-up berulang (Sangat Kurang)', 'value' => 1],
            ['criteria_code' => 'C5', 'name' => 'Respon > 6 jam atau esok hari (Kurang)', 'value' => 2],
            ['criteria_code' => 'C5', 'name' => 'Respon 3 – 6 jam, standar (Cukup)', 'value' => 3],
            ['criteria_code' => 'C5', 'name' => 'Respon 1 – 3 jam, proaktif (Baik)', 'value' => 4],
            ['criteria_code' => 'C5', 'name' => 'Respon < 1 jam, sangat proaktif (Sangat Baik)', 'value' => 5],

            ['criteria_code' => 'C6', 'name' => 'Tidak ada garansi atau klaim sangat sulit diproses (Sangat Kurang)', 'value' => 1],
            ['criteria_code' => 'C6', 'name' => 'Klaim sulit/berbelit, garansi 3 bulan atau kurang (Kurang)', 'value' => 2],
            ['criteria_code' => 'C6', 'name' => 'Klaim standar, garansi 6 bulan, dukungan teknis terbatas (Cukup)', 'value' => 3],
            ['criteria_code' => 'C6', 'name' => 'Klaim mudah, garansi 9-12 bulan, dukungan teknis pada jam kerja (Baik)', 'value' => 4],
            ['criteria_code' => 'C6', 'name' => 'Klaim sangat mudah, garansi minimal 12 bulan, dukungan teknis 24/7 (Sangat Baik)', 'value' => 5],
        ];

        $counters = [];

        foreach ($subCriteria as $item) {
            $criteria = DB::table('criterias')->where('code', $item['criteria_code'])->first();

            if ($criteria) {
                $counters[$criteria->id] = ($counters[$criteria->id] ?? 0) + 1;
                $code = strtoupper($item['criteria_code']) . '-' . str_pad((string) $counters[$criteria->id], 3, '0', STR_PAD_LEFT);

                DB::table('sub_criterias')->insert([
                    'criteria_id' => $criteria->id,
                    'code' => $code,
                    'name' => $item['name'],
                    'value' => $item['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }
        }
    }
}
