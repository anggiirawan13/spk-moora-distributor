<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['code', 'name', 'description'];
    }

    public function array(): array
    {
        return [
            ['P001', 'Miniature Circuit Breaker (MCB)', 'Breaker untuk proteksi arus rendah (6A-32A)'],
            ['P002', 'Molded Case Circuit Breaker (MCCB)', 'Breaker untuk proteksi arus menengah (100A-400A)'],
            ['P003', 'Contactor Elektrik', 'Kontaktor utama untuk rangkaian daya'],
        ];
    }

    public function title(): string
    {
        return 'Produk';
    }
}
