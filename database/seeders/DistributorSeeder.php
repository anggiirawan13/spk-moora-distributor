<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DistributorSeeder extends Seeder
{
    public static function data(): array
    {
        $faker = Faker::create('id_ID');
        $faker->seed(2026);

        $paymentTerms = array_column(\Database\Seeders\PaymentTermSeeder::data(), 'name');
        $deliveryMethods = array_column(\Database\Seeders\DeliveryMethodSeeder::data(), 'name');
        $businessScales = array_column(\Database\Seeders\BusinessScaleSeeder::data(), 'name');

        $distributors = [            
            'PT Arvanta Prima',
            'PT Kaluna Mandira',
            'PT Soreva Nusantara',
            'PT Talora Cipta',
            'PT Nuvexa Raya',
            'PT Laksora Abadi',
            'PT Velora Sentra',
            'PT Armeta Global',
            'PT Zafira Mandala',
            'PT Kireva Persada',
            'PT Solvanta Utama',
            'PT Nerava Lestari',
            'PT Trivora Jaya',
            'PT Alvion Sentosa',
            'PT Vireza Nusantara',
            'PT Karsena Prima',
            'PT Elvara Mandiri',
            'PT Ravento Cipta',
            'PT Nexara Abadi',
            'PT Arxena Global'
        ];

        $rows = [];
        foreach ($distributors as $index => $name) {
            $companyName = str_replace('PT ', '', $name);
            $npwp = $faker->numerify('###############');
            $distCode = 'D' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);

            $paymentTerm = $paymentTerms[$faker->numberBetween(1, count($paymentTerms)) - 1];
            $deliveryMethod = $deliveryMethods[$faker->numberBetween(1, count($deliveryMethods)) - 1];
            $businessScale = $businessScales[$faker->numberBetween(1, count($businessScales)) - 1];

            $rows[] = [
                'code' => $distCode,
                'name' => $name,
                'npwp' => $npwp,
                'email' => strtolower(str_replace(' ', '', $companyName)) . '@' . $faker->domainName(),
                'phone' => $faker->numerify('08#########'),
                'address' => $faker->address(),
                'payment_term' => $paymentTerm,
                'delivery_method' => $deliveryMethod,
                'business_scale' => $businessScale,
                'description' => $faker->optional()->paragraph(1),
                'is_active' => $faker->boolean(90) ? 1 : 0,
            ];
        }

        return $rows;
    }

    public function run(): void
    {
        $paymentTerms = DB::table('payment_terms')->pluck('id', 'name');
        $deliveryMethods = DB::table('delivery_methods')->pluck('id', 'name');
        $businessScales = DB::table('business_scales')->pluck('id', 'name');

        $rows = [];
        foreach (self::data() as $row) {
            $rows[] = [
                'name' => $row['name'],
                'dist_code' => $row['code'],
                'image_name' => 'Ex1y3eAnda4xT6AiP1j93VCJ9HQpTEmBMzaMLJsf.jpg',
                'npwp' => $row['npwp'],
                'address' => $row['address'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'payment_term_id' => $paymentTerms[$row['payment_term']] ?? null,
                'delivery_method_id' => $deliveryMethods[$row['delivery_method']] ?? null,
                'business_scale_id' => $businessScales[$row['business_scale']] ?? null,
                'description' => $row['description'],
                'is_active' => (bool) $row['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ];
        }

        DB::table('distributors')->insert($rows);
    }
}
