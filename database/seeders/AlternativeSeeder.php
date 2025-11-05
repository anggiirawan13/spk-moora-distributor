<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AlternativeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $distributorIds = DB::table('distributors')->pluck('id');

        foreach ($distributorIds as $distributorId) {
            $alternativeId = DB::table('alternatives')->insertGetId([
                'distributor_id' => $distributorId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $criteriaList = DB::table('criterias')->get();

            foreach ($criteriaList as $criteria) {
                $subCriteria = DB::table('sub_criterias')
                    ->where('criteria_id', $criteria->id)
                    ->inRandomOrder()
                    ->first();

                if (!$subCriteria) {
                    continue;
                }

                DB::table('alternative_values')->insert([
                    'alternative_id'   => $alternativeId,
                    'sub_criteria_id'  => $subCriteria->id,
                    'value'            => $subCriteria->value ?? $faker->numberBetween(1, 5),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }
}
