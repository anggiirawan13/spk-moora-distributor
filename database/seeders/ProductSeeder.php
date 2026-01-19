<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Miniature Circuit Breaker (MCB)', 'description' => 'Breaker untuk proteksi arus rendah (6A-32A)', 'category' => 'MCB/MCCB'],
            ['name' => 'Molded Case Circuit Breaker (MCCB)', 'description' => 'Breaker untuk proteksi arus menengah (100A-400A)', 'category' => 'MCB/MCCB'],
            ['name' => 'Contactor Elektrik', 'description' => 'Kontaktor utama untuk rangkaian daya', 'category' => 'Contactor/Relay'],
            ['name' => 'Thermal Overload Relay', 'description' => 'Relay pengaman motor dari beban lebih', 'category' => 'Contactor/Relay'],
            ['name' => 'Miniatur Relay (24VDC/220VAC)', 'description' => 'Relay kontrol umum', 'category' => 'Contactor/Relay'],
            ['name' => 'Kabel NYY', 'description' => 'Kabel instalasi power non-fleksibel', 'category' => 'Kabel'],
            ['name' => 'Kabel NYAF', 'description' => 'Kabel fleksibel instalasi panel', 'category' => 'Kabel'],
            ['name' => 'Kabel Kontrol CY', 'description' => 'Kabel kontrol berselubung perisai', 'category' => 'Kabel'],
            ['name' => 'Proximity Sensor', 'description' => 'Sensor pendeteksi tanpa sentuhan', 'category' => 'Sensor/Kontrol'],
            ['name' => 'Limit Switch', 'description' => 'Saklar batas mekanis', 'category' => 'Sensor/Kontrol'],
            ['name' => 'Timer Digital', 'description' => 'Pengatur waktu On/Off digital', 'category' => 'Sensor/Kontrol'],
            ['name' => 'Panel Box Standard', 'description' => 'Box panel standar (IP54)', 'category' => 'Panel/Aksesoris'],
            ['name' => 'Busbar Tembaga', 'description' => 'Konduktor penghubung utama di panel', 'category' => 'Panel/Aksesoris'],
            ['name' => 'Indicator Light/Pilot Lamp', 'description' => 'Lampu indikator status panel (Merah/Hijau/Kuning)', 'category' => 'Panel/Aksesoris'],
            ['name' => 'Cable Gland & Lug', 'description' => 'Aksesoris terminasi kabel', 'category' => 'Panel/Aksesoris'],
        ];

        $insertData = [];
        foreach ($products as $product) {
            $insertData[] = [
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