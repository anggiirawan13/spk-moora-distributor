<?php

namespace App\Imports;

use App\Imports\Sheets\AlternativeSheetImport;
use App\Imports\Sheets\BusinessScaleSheetImport;
use App\Imports\Sheets\CriteriaSheetImport;
use App\Imports\Sheets\DeliveryMethodSheetImport;
use App\Imports\Sheets\DistributorSheetImport;
use App\Imports\Sheets\DistributorProductSheetImport;
use App\Imports\Sheets\PaymentTermSheetImport;
use App\Imports\Sheets\ProductSheetImport;
use App\Imports\Sheets\SubCriteriaSheetImport;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Events\BeforeImport;

class MasterImport implements WithMultipleSheets, SkipsUnknownSheets, WithEvents
{
    private const REQUIRED_SHEETS = [
        'Skala_Bisnis',
        'Metode_Pengiriman',
        'Termin_Pembayaran',
        'Distributor',
        'Produk',
        'Distributor_Produk',
        'Kriteria',
        'Sub_Kriteria',
        'Alternatif',
    ];

    public function __construct(
        private readonly ImportErrorBag $errors,
        private readonly ImportStats $stats,
        private readonly ImportContext $context,
        private readonly bool $dryRun = false
    )
    {
    }

    public function sheets(): array
    {
        return [
            'Skala_Bisnis' => new BusinessScaleSheetImport($this->errors, $this->stats, $this->context, $this->dryRun),
            'Metode_Pengiriman' => new DeliveryMethodSheetImport($this->errors, $this->stats, $this->context, $this->dryRun),
            'Termin_Pembayaran' => new PaymentTermSheetImport($this->errors, $this->stats, $this->context, $this->dryRun),
            'Distributor' => new DistributorSheetImport($this->errors, $this->stats, $this->context, $this->dryRun),
            'Produk' => new ProductSheetImport($this->errors, $this->stats, $this->context, $this->dryRun),
            'Distributor_Produk' => new DistributorProductSheetImport($this->errors, $this->stats, $this->context, $this->dryRun),
            'Kriteria' => new CriteriaSheetImport($this->errors, $this->stats, $this->context, $this->dryRun),
            'Sub_Kriteria' => new SubCriteriaSheetImport($this->errors, $this->stats, $this->context, $this->dryRun),
            'Alternatif' => new AlternativeSheetImport($this->errors, $this->stats, $this->context, $this->dryRun),
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $sheetNames = $event->reader->getSheetNames();

                if ($sheetNames !== self::REQUIRED_SHEETS) {
                    $expected = implode(', ', self::REQUIRED_SHEETS);
                    $found = implode(', ', $sheetNames);
                    $this->context->abort = true;
                    $this->context->abortReason = "Urutan sheet wajib: {$expected}. Ditemukan: {$found}";
                    $this->errors->add('SYSTEM', 0, $this->context->abortReason);
                }
            },
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // Ignore unknown sheets.
    }
}
