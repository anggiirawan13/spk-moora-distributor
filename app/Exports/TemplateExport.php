<?php

namespace App\Exports;

use App\Exports\Sheets\AlternativeSheetTemplate;
use App\Exports\Sheets\BusinessScaleSheetTemplate;
use App\Exports\Sheets\CriteriaSheetTemplate;
use App\Exports\Sheets\DeliveryMethodSheetTemplate;
use App\Exports\Sheets\DistributorSheetTemplate;
use App\Exports\Sheets\PaymentTermSheetTemplate;
use App\Exports\Sheets\ReadmeSheetTemplate;
use App\Exports\Sheets\SubCriteriaSheetTemplate;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new ReadmeSheetTemplate(),
            new BusinessScaleSheetTemplate(),
            new DeliveryMethodSheetTemplate(),
            new PaymentTermSheetTemplate(),
            new DistributorSheetTemplate(),
            new CriteriaSheetTemplate(),
            new SubCriteriaSheetTemplate(),
            new AlternativeSheetTemplate(),
        ];
    }
}
