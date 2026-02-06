<?php

namespace App\Imports;

use App\Imports\Sheets\AlternativeSheetImport;
use App\Imports\Sheets\BusinessScaleSheetImport;
use App\Imports\Sheets\CriteriaSheetImport;
use App\Imports\Sheets\DeliveryMethodSheetImport;
use App\Imports\Sheets\DistributorSheetImport;
use App\Imports\Sheets\PaymentTermSheetImport;
use App\Imports\Sheets\SubCriteriaSheetImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class MasterImport implements WithMultipleSheets, SkipsUnknownSheets
{
    public function __construct(
        private readonly ImportErrorBag $errors,
        private readonly ImportStats $stats,
        private readonly bool $dryRun = false
    )
    {
    }

    public function sheets(): array
    {
        return [
            'business_scales' => new BusinessScaleSheetImport($this->errors, $this->stats, $this->dryRun),
            'delivery_methods' => new DeliveryMethodSheetImport($this->errors, $this->stats, $this->dryRun),
            'payment_terms' => new PaymentTermSheetImport($this->errors, $this->stats, $this->dryRun),
            'distributors' => new DistributorSheetImport($this->errors, $this->stats, $this->dryRun),
            'criterias' => new CriteriaSheetImport($this->errors, $this->stats, $this->dryRun),
            'sub_criteria' => new SubCriteriaSheetImport($this->errors, $this->stats, $this->dryRun),
            'alternatives' => new AlternativeSheetImport($this->errors, $this->stats, $this->dryRun),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // Ignore unknown sheets.
    }
}
