<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BusinessScaleSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['name', 'description'];
    }

    public function array(): array
    {
        return [
            ['Distributor Nasional', 'Distributor dengan jangkauan seluruh Indonesia'],
            ['Distributor Regional', 'Distributor dengan jangkauan beberapa provinsi'],
            ['Distributor Lokal', 'Distributor dengan jangkauan dalam satu kota/kabupaten'],
        ];
    }

    public function title(): string
    {
        return 'Skala Bisnis';
    }
}
