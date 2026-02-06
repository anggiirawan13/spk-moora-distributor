<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentTermSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['name', 'description'];
    }

    public function array(): array
    {
        return [
            ['Cash / Tunai', 'Pembayaran secara tunai saat transaksi'],
            ['COD (Cash on Delivery)', 'Pembayaran saat barang diterima'],
            ['Net 30 Hari', 'Pembayaran dalam jangka waktu 30 hari setelah pengiriman'],
        ];
    }

    public function title(): string
    {
        return 'Termin Pembayaran';
    }
}
