<?php

namespace App\Imports\Sheets;

use App\Imports\ImportErrorBag;
use App\Imports\ImportContext;
use App\Imports\ImportStats;
use App\Models\PaymentTerm;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PaymentTermSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private const SHEET = 'Metode Pengiriman';
    private array $seenCodes = [];

    public function __construct(
        private readonly ImportErrorBag $errors,
        private readonly ImportStats $stats,
        private readonly ImportContext $context,
        private readonly bool $dryRun
    )
    {
    }

    public function collection(Collection $rows)
    {
        if ($this->context->abort) {
            return;
        }

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $code = trim((string) ($row['code'] ?? ''));
            $name = trim((string) ($row['name'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));

            if ($code === '') {
                $this->errors->add(self::SHEET, $rowNumber, 'Kode kosong');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }
            
            if ($name === '') {
                $this->errors->add(self::SHEET, $rowNumber, 'Nama kosong');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if (isset($this->seenCodes[$code])) {
                $this->errors->add(self::SHEET, $rowNumber, "Duplikat di file: {$code}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $this->seenCodes[$code] = true;

            if (PaymentTerm::where('code', $code)->exists()) {
                $this->errors->add(self::SHEET, $rowNumber, "Kode sudah ada: {$code}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate(self::SHEET);
                $this->stats->addSample(self::SHEET, [
                    'code' => $code,
                    'name' => $name,
                    'description' => $description !== '' ? $description : null,
                ]);
                $this->context->paymentTerms[$code] = true;
                continue;
            }

            PaymentTerm::create([
                'code' => $code,
                'name' => $name,
                'description' => $description !== '' ? $description : null,
            ]);
            $this->stats->addCreated(self::SHEET);
            $this->context->paymentTerms[$code] = true;
        }
    }
}
