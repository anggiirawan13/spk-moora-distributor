<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DeliveryMethodSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['code', 'name', 'description'];
    }

    public function array(): array
    {
        return [
            ['MP001', 'Pengiriman Ekspres (1-2 hari)', 'Pengiriman cepat dengan estimasi 1-2 hari kerja'],
            ['MP002', 'Pengiriman Reguler (3-5 hari)', 'Pengiriman standar dengan estimasi 3-5 hari kerja'],
            ['MP003', 'Pickup Mandiri', 'Pelanggan mengambil barang langsung ke gudang distributor'],
        ];
    }

    public function title(): string
    {
        return 'Metode Pengiriman';
    }
}
