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
            'payment_term_code',
            'delivery_method_code',
            'business_scale_code',
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
                'TP001',
                'MP001',
                'SB001',
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
                'TP002',
                'MP002',
                'SB002',
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
