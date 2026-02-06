<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AlternativeSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['code', 'criteria_code', 'sub_criteria_code'];
    }

    public function array(): array
    {
        return [
            ['D001', 'C1', 'C1-001'],
            ['D001', 'C2', 'C2-002'],
            ['D001', 'C3', 'C3-001'],
            ['D002', 'C1', 'C1-003'],
            ['D002', 'C2', 'C2-001'],
            ['D002', 'C3', 'C3-001'],
        ];
    }

    public function title(): string
    {
        return 'Alternatif';
    }
}
