<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DistributorProductSeeder extends Seeder
{
    public function run(): void
    {
        // Mendefinisikan mapping produk untuk setiap Distributor ID
        // (Berdasarkan analisis produk general yang Anda berikan)
        // Distributor ID 1 hingga 20 sesuai dengan urutan di DistributorSeeder

        $distributorProductMapping = [
            // PT Sinar Elektrik Jaya (ID 1)
            1 => [1, 3, 4, 5, 6, 10, 14, 15],

            // PT Mega Teknik Abadi (ID 2)
            2 => [1, 7, 9, 11, 13, 15],

            // PT Cahaya Makmur Electric (ID 3)
            3 => [1, 2, 3, 12, 13, 14],

            // PT Prima Sumber Teknik (ID 4)
            4 => [2, 3, 7, 11, 14, 15],

            // PT Bentang Listrik Nusantara (ID 5)
            5 => [3, 4, 5, 6, 14],

            // PT Teknologi Cahaya Persada (ID 6)
            6 => [9, 10, 11], // Fokus pada sensor/kontrol

            // PT Indotek Perkasa Mandiri (ID 7)
            7 => [1, 3, 6, 12, 15],

            // PT Energi Power System (ID 8)
            8 => [1, 5, 6], // Fokus pada produk DC/Power

            // PT Mitra Global Teknik (ID 9)
            9 => [2, 7, 11, 13, 15],

            // PT Pionir Elektrik Nusantara (ID 10)
            10 => [1, 5, 8, 14, 15],

            // PT Delta Mandiri Electric (ID 11)
            11 => [3, 5, 8, 9, 10, 11],

            // PT Adi Sentosa Electric (ID 12)
            12 => [2, 14], // Fokus MCCB/Breaker besar

            // PT Utama Teknik Indonesia (ID 13)
            13 => [1, 3, 4, 5, 6, 14],

            // PT Surya Mandiri Electric (ID 14)
            14 => [1, 3, 8, 12, 13, 15],

            // PT Bintang Power Solution (ID 15)
            15 => [2, 3, 4, 11, 14],

            // PT Global Teknik Lestari (ID 16)
            16 => [3, 8, 9, 10], // Fokus PLC/Kontrol

            // PT Mandiri Cahaya Sakti (ID 17)
            17 => [1, 3, 5, 8, 14],

            // PT Acosta Elektro Supply (ID 18)
            18 => [1, 3, 4, 8, 14, 15],

            // PT Nusantara Powerindo (ID 19)
            19 => [1, 2, 13, 15],

            // PT Inti Jaya Sakti Electric (ID 20)
            20 => [1, 3, 5, 14, 15],
        ];

        // Kosongkan tabel untuk mencegah duplikasi (opsional, tergantung setup)
        // DB::table('distributor_product')->truncate();

        $dataToInsert = [];
        foreach ($distributorProductMapping as $distributorId => $productIds) {
            foreach ($productIds as $productId) {
                $dataToInsert[] = [
                    'distributor_id' => $distributorId,
                    'product_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => 1,
                    'updated_by' => 1,
                ];
            }
        }

        DB::table('distributor_product')->insert($dataToInsert);
    }
}