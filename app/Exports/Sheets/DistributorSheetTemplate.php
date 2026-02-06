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
                'PT Sinar Elektrik Jaya',
                '123456789012345',
                'sinarelektrikjaya@contoh.co.id',
                '081234567890',
                'Jl. Contoh No. 1, Jakarta',
                'Cash / Tunai',
                'Pengiriman Ekspres (1-2 hari)',
                'Distributor Nasional',
                'Distributor utama',
                '1',
            ],
            [
                'D002',
                'PT Mega Teknik Abadi',
                '987654321098765',
                'megateknikabadi@contoh.co.id',
                '6281234567890',
                'Jl. Listrik No. 2, Bandung',
                'Net 30 Hari',
                'Pengiriman Reguler (3-5 hari)',
                'Distributor Regional',
                'Distributor regional',
                '1',
            ],
        ];
    }

    public function title(): string
    {
        return 'Distributor';
    }
}
