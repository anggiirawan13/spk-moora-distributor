<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DistributorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $distributors = [
            'PT Sinar Elektrik Jaya',
            'PT Mega Teknik Abadi',
            'PT Cahaya Makmur Electric',
            'PT Prima Sumber Teknik',
            'PT Bentang Listrik Nusantara',
            'PT Teknologi Cahaya Persada',
            'PT Indotek Perkasa Mandiri',
            'PT Energi Power System',
            'PT Mitra Global Teknik',
            'PT Pionir Elektrik Nusantara',
            'PT Delta Mandiri Electric',
            'PT Adi Sentosa Electric',
            'PT Utama Teknik Indonesia',
            'PT Surya Mandiri Electric',
            'PT Bintang Power Solution',
            'PT Global Teknik Lestari',
            'PT Mandiri Cahaya Sakti',
            'PT Acosta Elektro Supply',
            'PT Nusantara Powerindo',
            'PT Inti Jaya Sakti Electric',
        ];

        foreach ($distributors as $name) {

            $companyName = str_replace('PT ', '', $name);

            DB::table('distributors')->insert([
                'name' => $name,
                'image_name' => 'Ex1y3eAnda4xT6AiP1j93VCJ9HQpTEmBMzaMLJsf.jpg',
                'company_name' => $companyName,
                'address' => $faker->address(),
                'phone' => $faker->numerify('08#########'),
                'email' => strtolower(str_replace(' ', '', $companyName)) . '@' . $faker->domainName(),
                'payment_term_id' => $faker->numberBetween(1, 5),
                'delivery_method_id' => $faker->numberBetween(1, 4),
                'business_scale_id' => $faker->numberBetween(1, 5),
                'description' => $faker->optional()->paragraph(1),
                'is_active' => $faker->boolean(90),
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }
}