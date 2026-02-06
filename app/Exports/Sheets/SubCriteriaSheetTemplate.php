<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SubCriteriaSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['criteria_code', 'name', 'value'];
    }

    public function array(): array
    {
        return [
            ['C1', 'Murah', '5'],
            ['C1', 'Sedang', '3'],
            ['C1', 'Mahal', '1'],
            ['C2', 'Sangat Baik', '5'],
            ['C2', 'Baik', '3'],
            ['C3', 'Cepat', '5'],
        ];
    }

    public function title(): string
    {
        return 'sub_criteria';
    }
}
