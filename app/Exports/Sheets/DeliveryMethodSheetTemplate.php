<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DeliveryMethodSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['name', 'description'];
    }

    public function array(): array
    {
        return [
            ['Pengiriman Ekspres (1-2 hari)', 'Pengiriman cepat dengan estimasi 1-2 hari kerja'],
            ['Pengiriman Reguler (3-5 hari)', 'Pengiriman standar dengan estimasi 3-5 hari kerja'],
            ['Pickup Mandiri', 'Pelanggan mengambil barang langsung ke gudang distributor'],
        ];
    }

    public function title(): string
    {
        return 'Metode Pengiriman';
    }
}
