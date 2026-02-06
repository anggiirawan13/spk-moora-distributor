<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentTermSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['name', 'description'];
    }

    public function array(): array
    {
        return [
            ['Net 30', 'Pembayaran 30 hari'],
            ['Net 15', 'Pembayaran 15 hari'],
            ['COD', 'Cash on delivery'],
        ];
    }

    public function title(): string
    {
        return 'payment_terms';
    }
}
