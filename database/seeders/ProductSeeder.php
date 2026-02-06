<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public static function data(): array
    {
        return [
            ['code' => 'P001', 'name' => 'Miniature Circuit Breaker (MCB)', 'description' => 'Breaker untuk proteksi arus rendah (6A-32A)'],
            ['code' => 'P002', 'name' => 'Molded Case Circuit Breaker (MCCB)', 'description' => 'Breaker untuk proteksi arus menengah (100A-400A)'],
            ['code' => 'P003', 'name' => 'Contactor Elektrik', 'description' => 'Kontaktor utama untuk rangkaian daya'],
            ['code' => 'P004', 'name' => 'Thermal Overload Relay', 'description' => 'Relay pengaman motor dari beban lebih'],
            ['code' => 'P005', 'name' => 'Miniatur Relay (24VDC/220VAC)', 'description' => 'Relay kontrol umum'],
            ['code' => 'P006', 'name' => 'Kabel NYY', 'description' => 'Kabel instalasi power non-fleksibel'],
            ['code' => 'P007', 'name' => 'Kabel NYAF', 'description' => 'Kabel fleksibel instalasi panel'],
            ['code' => 'P008', 'name' => 'Kabel Kontrol CY', 'description' => 'Kabel kontrol berselubung perisai'],
            ['code' => 'P009', 'name' => 'Proximity Sensor', 'description' => 'Sensor pendeteksi tanpa sentuhan'],
            ['code' => 'P010', 'name' => 'Limit Switch', 'description' => 'Saklar batas mekanis'],
            ['code' => 'P011', 'name' => 'Timer Digital', 'description' => 'Pengatur waktu On/Off digital'],
            ['code' => 'P012', 'name' => 'Panel Box Standard', 'description' => 'Box panel standar (IP54)'],
            ['code' => 'P013', 'name' => 'Busbar Tembaga', 'description' => 'Konduktor penghubung utama di panel'],
            ['code' => 'P014', 'name' => 'Indicator Light/Pilot Lamp', 'description' => 'Lampu indikator status panel (Merah/Hijau/Kuning)'],
            ['code' => 'P015', 'name' => 'Cable Gland & Lug', 'description' => 'Aksesoris terminasi kabel'],
        ];
    }

    public function run(): void
    {
        $products = self::data();

        $insertData = [];
        foreach ($products as $product) {
            $insertData[] = [
                'code' => $product['code'],
                'name' => $product['name'],
                'description' => $product['description'],
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ];
        }

        DB::table('products')->insert($insertData);
    }
}
