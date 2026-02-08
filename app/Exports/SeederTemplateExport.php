<?php

namespace App\Exports;

use App\Exports\Sheets\SeederArraySheet;
use Database\Seeders\AlternativeSeeder;
use Database\Seeders\BusinessScaleSeeder;
use Database\Seeders\CriteriaSeeder;
use Database\Seeders\DeliveryMethodSeeder;
use Database\Seeders\DistributorProductSeeder;
use Database\Seeders\DistributorSeeder;
use Database\Seeders\PaymentTermSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\SubCriteriaSeeder;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SeederTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new SeederArraySheet('Skala Bisnis', ['code', 'name', 'description'], BusinessScaleSeeder::data()),
            new SeederArraySheet('Metode Pengiriman', ['code', 'name', 'description'], DeliveryMethodSeeder::data()),
            new SeederArraySheet('Termin Pembayaran', ['code', 'name', 'description'], PaymentTermSeeder::data()),
            new SeederArraySheet('Distributor', [
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
            ], DistributorSeeder::data()),
            new SeederArraySheet('Produk', ['code', 'name', 'description'], ProductSeeder::data()),
            new SeederArraySheet('Distributor Produk', ['distributor_code', 'product_code'], DistributorProductSeeder::data()),
            new SeederArraySheet('Kriteria', ['code', 'name', 'weight', 'attribute_type'], CriteriaSeeder::data()),
            new SeederArraySheet('Sub Kriteria', ['criteria_code', 'code', 'name', 'value'], SubCriteriaSeeder::data()),
            new SeederArraySheet('Alternatif', ['code', 'criteria_code', 'sub_criteria_code'], AlternativeSeeder::data()),
        ];
    }
}
