<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DistributorSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'code',
            'name',
            'npwp',
            'email',
            'phone',
            'address',
            'payment_term',
            'delivery_method',
            'business_scale',
            'description',
            'is_active',
        ];
    }

    public function array(): array
    {
        return [
            [
                'D001',
                'PT Contoh Distributor',
                '123456789012345',
                'contoh@distributor.co.id',
                '081234567890',
                'Jl. Contoh No. 1, Jakarta',
                'Net 30',
                'Ekspedisi',
                'Besar',
                'Distributor utama',
                '1',
            ],
            [
                'D002',
                'PT Sinar Elektrik',
                '987654321098765',
                'sinar@distributor.co.id',
                '6281234567890',
                'Jl. Listrik No. 2, Bandung',
                'Net 15',
                'Kurir',
                'Menengah',
                'Distributor regional',
                '1',
            ],
        ];
    }

    public function title(): string
    {
        return 'distributors';
    }
}
