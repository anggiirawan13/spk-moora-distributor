<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlternativeSeeder extends Seeder
{
    public function run(): void
    {
        $alternatives = [
            ['name' => 'PT Sinar Elektrik Jaya', 'C1' => 2, 'C2' => 3, 'C3' => 1, 'C4' => 5, 'C5' => 3, 'C6' => 3],
            ['name' => 'PT Mega Teknik Abadi', 'C1' => 5, 'C2' => 3, 'C3' => 1, 'C4' => 2, 'C5' => 3, 'C6' => 4],
            ['name' => 'PT Cahaya Makmur Electric', 'C1' => 4, 'C2' => 1, 'C3' => 1, 'C4' => 2, 'C5' => 2, 'C6' => 5],
            ['name' => 'PT Prima Sumber Teknik', 'C1' => 3, 'C2' => 4, 'C3' => 1, 'C4' => 1, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Bentang Listrik Nusantara', 'C1' => 5, 'C2' => 5, 'C3' => 1, 'C4' => 3, 'C5' => 1, 'C6' => 2],
            ['name' => 'PT Teknologi Cahaya Persada', 'C1' => 3, 'C2' => 3, 'C3' => 1, 'C4' => 4, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Indotek Perkasa Mandiri', 'C1' => 4, 'C2' => 3, 'C3' => 1, 'C4' => 5, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Energi Power System', 'C1' => 5, 'C2' => 1, 'C3' => 1, 'C4' => 5, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Mitra Global Teknik', 'C1' => 4, 'C2' => 5, 'C3' => 1, 'C4' => 5, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Pionir Elektrik Nusantara', 'C1' => 4, 'C2' => 2, 'C3' => 1, 'C4' => 2, 'C5' => 2, 'C6' => 3],
            ['name' => 'PT Delta Mandiri Electric', 'C1' => 3, 'C2' => 2, 'C3' => 1, 'C4' => 4, 'C5' => 1, 'C6' => 5],
            ['name' => 'PT Adi Sentosa Electric', 'C1' => 4, 'C2' => 2, 'C3' => 1, 'C4' => 1, 'C5' => 5, 'C6' => 1],
            ['name' => 'PT Utama Teknik Indonesia', 'C1' => 5, 'C2' => 5, 'C3' => 1, 'C4' => 3, 'C5' => 5, 'C6' => 2],
            ['name' => 'PT Surya Mandiri Electric', 'C1' => 3, 'C2' => 2, 'C3' => 1, 'C4' => 2, 'C5' => 1, 'C6' => 4],
            ['name' => 'PT Bintang Power Solution', 'C1' => 3, 'C2' => 4, 'C3' => 1, 'C4' => 1, 'C5' => 4, 'C6' => 5],
            ['name' => 'PT Global Teknik Lestari', 'C1' => 2, 'C2' => 1, 'C3' => 1, 'C4' => 5, 'C5' => 3, 'C6' => 2],
            ['name' => 'PT Mandiri Cahaya Sakti', 'C1' => 2, 'C2' => 2, 'C3' => 1, 'C4' => 4, 'C5' => 1, 'C6' => 1],
            ['name' => 'PT Acosta Elektro Supply', 'C1' => 1, 'C2' => 3, 'C3' => 1, 'C4' => 4, 'C5' => 5, 'C6' => 5],
            ['name' => 'PT Nusantara Powerindo', 'C1' => 5, 'C2' => 1, 'C3' => 1, 'C4' => 4, 'C5' => 1, 'C6' => 3],
            ['name' => 'PT Inti Jaya Sakti Electric', 'C1' => 5, 'C2' => 1, 'C3' => 1, 'C4' => 1, 'C5' => 4, 'C6' => 1],
        ];

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
