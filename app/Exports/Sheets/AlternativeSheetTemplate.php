<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AlternativeSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['code', 'criteria_code', 'sub_criteria_name'];
    }

    public function array(): array
    {
        return [
            ['D001', 'C1', 'Murah'],
            ['D001', 'C2', 'Baik'],
            ['D001', 'C3', 'Cepat'],
            ['D002', 'C1', 'Sedang'],
            ['D002', 'C2', 'Sangat Baik'],
            ['D002', 'C3', 'Cepat'],
        ];
    }

    public function title(): string
    {
        return 'alternatives';
    }
}
