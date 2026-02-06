<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlternativeSeeder extends Seeder
{
    private static function seedMatrix(): array
    {
        return [
            ['name' => 'PT Arvanta Prima', 'C1' => 2, 'C2' => 3, 'C3' => 1, 'C4' => 5, 'C5' => 3, 'C6' => 3],
            ['name' => 'PT Kaluna Mandira', 'C1' => 5, 'C2' => 3, 'C3' => 1, 'C4' => 2, 'C5' => 3, 'C6' => 4],
            ['name' => 'PT Soreva Nusantara', 'C1' => 4, 'C2' => 1, 'C3' => 1, 'C4' => 2, 'C5' => 2, 'C6' => 5],
            ['name' => 'PT Talora Cipta', 'C1' => 3, 'C2' => 4, 'C3' => 1, 'C4' => 1, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Nuvexa Raya', 'C1' => 5, 'C2' => 5, 'C3' => 1, 'C4' => 3, 'C5' => 1, 'C6' => 2],
            ['name' => 'PT Laksora Abadi', 'C1' => 3, 'C2' => 3, 'C3' => 1, 'C4' => 4, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Velora Sentra', 'C1' => 4, 'C2' => 3, 'C3' => 1, 'C4' => 5, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Armeta Global', 'C1' => 5, 'C2' => 1, 'C3' => 1, 'C4' => 5, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Zafira Mandala', 'C1' => 4, 'C2' => 5, 'C3' => 1, 'C4' => 5, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Kireva Persada', 'C1' => 4, 'C2' => 2, 'C3' => 1, 'C4' => 2, 'C5' => 2, 'C6' => 3],
            ['name' => 'PT Solvanta Utama', 'C1' => 3, 'C2' => 2, 'C3' => 1, 'C4' => 4, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Nerava Lestari', 'C1' => 4, 'C2' => 2, 'C3' => 1, 'C4' => 1, 'C5' => 5, 'C6' => 1],
            ['name' => 'PT Trivora Jaya', 'C1' => 5, 'C2' => 5, 'C3' => 1, 'C4' => 3, 'C5' => 5, 'C6' => 2],
            ['name' => 'PT Alvion Sentosa', 'C1' => 3, 'C2' => 2, 'C3' => 1, 'C4' => 2, 'C5' => 1, 'C6' => 4],
            ['name' => 'PT Vireza Nusantara', 'C1' => 3, 'C2' => 4, 'C3' => 1, 'C4' => 1, 'C5' => 4, 'C6' => 5],
            ['name' => 'PT Karsena Prima', 'C1' => 2, 'C2' => 1, 'C3' => 1, 'C4' => 5, 'C5' => 3, 'C6' => 2],
            ['name' => 'PT Elvara Mandiri', 'C1' => 2, 'C2' => 2, 'C3' => 1, 'C4' => 4, 'C5' => 1, 'C6' => 1],
            ['name' => 'PT Ravento Cipta', 'C1' => 1, 'C2' => 3, 'C3' => 1, 'C4' => 4, 'C5' => 5, 'C6' => 5],
            ['name' => 'PT Nexara Abadi', 'C1' => 5, 'C2' => 1, 'C3' => 1, 'C4' => 4, 'C5' => 1, 'C6' => 3],
            ['name' => 'PT Arxena Global', 'C1' => 5, 'C2' => 1, 'C3' => 1, 'C4' => 1, 'C5' => 4, 'C6' => 1],
        ];
    }

    public static function data(): array
    {
        $distMap = [];
        foreach (\Database\Seeders\DistributorSeeder::data() as $row) {
            $distMap[$row['name']] = $row['code'];
        }

        $subMap = [];
        foreach (\Database\Seeders\SubCriteriaSeeder::data() as $row) {
            $subMap[$row['criteria_code'] . '|' . $row['value']] = $row['code'];
        }

        $rows = [];
        foreach (self::seedMatrix() as $alt) {
            $distCode = $distMap[$alt['name']] ?? null;
            if (!$distCode) {
                continue;
            }

            foreach (['C1', 'C2', 'C3', 'C4', 'C5', 'C6'] as $criteriaCode) {
                $subCode = $subMap[$criteriaCode . '|' . $alt[$criteriaCode]] ?? null;
                if (!$subCode) {
                    continue;
                }

                $rows[] = [
                    'code' => $distCode,
                    'criteria_code' => $criteriaCode,
                    'sub_criteria_code' => $subCode,
                ];
            }
        }

        return $rows;
    }

    public function run(): void
    {
        $alternatives = self::seedMatrix();

        $criteriaList = DB::table('criterias')->get()->keyBy('code');

        foreach ($alternatives as $alt) {
            $distributor = DB::table('distributors')->where('name', $alt['name'])->first();
            if (!$distributor)
                continue;

            $alternativeId = DB::table('alternatives')->insertGetId([
                'distributor_id' => $distributor->id,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            foreach (['C1', 'C2', 'C3', 'C4', 'C5', 'C6'] as $code) {
                $criteria = $criteriaList[$code] ?? null;
                if (!$criteria)
                    continue;

                $subCriteria = DB::table('sub_criterias')
                    ->where('criteria_id', $criteria->id)
                    ->where('value', $alt[$code])
                    ->first();

                if (!$subCriteria)
                    continue;

                DB::table('alternative_values')->insert([
                    'alternative_id' => $alternativeId,
                    'sub_criteria_id' => $subCriteria->id,
                    'value' => $subCriteria->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }
        }
    }
}
