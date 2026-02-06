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
            ['Ekspedisi', 'Pengiriman melalui ekspedisi'],
            ['Pickup', 'Pengambilan sendiri'],
            ['Kurir', 'Pengiriman kurir internal'],
        ];
    }

    public function title(): string
    {
        return 'delivery_methods';
    }
}
