<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTermSeeder extends Seeder
{
    public static function data(): array
    {
        return [
            [
                'code' => 'TP001',
                'name' => 'Cash / Tunai',
                'description' => 'Pembayaran secara tunai saat transaksi',
            ],
            [
                'code' => 'TP002',
                'name' => 'COD (Cash on Delivery)',
                'description' => 'Pembayaran saat barang diterima',
            ],
            [
                'code' => 'TP003',
                'name' => 'Net 7 Hari',
                'description' => 'Pembayaran dalam jangka waktu 7 hari setelah pengiriman',
            ],
            [
                'code' => 'TP004',
                'name' => 'Net 30 Hari',
                'description' => 'Pembayaran dalam jangka waktu 30 hari setelah pengiriman',
            ],
            [
                'code' => 'TP005',
                'name' => 'DP 50%, Pelunasan Sebelum Pengiriman',
                'description' => 'Down payment 50% dan pelunasan sebelum barang dikirim',
            ],
        ];
    }

    public function run(): void
    {
        $rows = [];
        foreach (self::data() as $row) {
            $rows[] = $row + [
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ];
        }

        DB::table('payment_terms')->insert($rows);
    }
}
