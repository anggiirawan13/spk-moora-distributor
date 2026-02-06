<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class CriteriaSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['code', 'name', 'weight', 'attribute_type'];
    }

    public function array(): array
    {
        return [
            ['C1', 'Harga', '30', 'Cost'],
            ['C2', 'Kualitas', '25', 'Benefit'],
            ['C3', 'Pengiriman', '20', 'Benefit'],
        ];
    }

    public function title(): string
    {
        return 'criterias';
    }
}
