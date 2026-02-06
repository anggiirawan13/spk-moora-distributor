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
            ['Besar', 'Skala bisnis besar'],
            ['Menengah', 'Skala bisnis menengah'],
            ['Kecil', 'Skala bisnis kecil'],
        ];
    }

    public function title(): string
    {
        return 'business_scales';
    }
}
