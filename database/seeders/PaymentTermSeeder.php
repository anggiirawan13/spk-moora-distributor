<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTermSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_terms')->insert([
            [
                'name' => 'Cash / Tunai',
                'description' => 'Pembayaran secara tunai saat transaksi',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'COD (Cash on Delivery)',
                'description' => 'Pembayaran saat barang diterima',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Net 7 Hari',
                'description' => 'Pembayaran dalam jangka waktu 7 hari setelah pengiriman',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Net 30 Hari',
                'description' => 'Pembayaran dalam jangka waktu 30 hari setelah pengiriman',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'DP 50%, Pelunasan Sebelum Pengiriman',
                'description' => 'Down payment 50% dan pelunasan sebelum barang dikirim',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]
        ]);
    }
}