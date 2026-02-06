<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DistributorProductSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['code', 'product_code'];
    }

    public function array(): array
    {
        return [
            ['D001', 'P001'],
            ['D001', 'P003'],
            ['D002', 'P002'],
        ];
    }

    public function title(): string
    {
        return 'Distributor Produk';
    }
}
