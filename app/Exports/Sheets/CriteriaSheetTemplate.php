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
            ['C1', 'Harga Produk', '0.30', 'Cost'],
            ['C2', 'Waktu Kirim', '0.25', 'Cost'],
            ['C3', 'Kualitas Produk', '0.20', 'Benefit'],
            ['C4', 'Responsivitas Layanan', '0.15', 'Benefit'],
            ['C5', 'Dukungan Purna Jual', '0.10', 'Benefit'],
        ];
    }

    public function title(): string
    {
        return 'Kriteria';
    }
}
