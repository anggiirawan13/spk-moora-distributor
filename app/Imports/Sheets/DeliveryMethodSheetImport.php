<?php

namespace App\Imports\Sheets;

use App\Imports\ImportContext;
use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\DeliveryMethod;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DeliveryMethodSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private const SHEET = 'Metode Pengiriman';
    private array $seenNames = [];

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
            $name = trim((string) ($row['name'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));

            if ($name === '') {
                $this->errors->add(self::SHEET, $rowNumber, 'Nama kosong');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if (isset($this->seenNames[$name])) {
                $this->errors->add(self::SHEET, $rowNumber, "Duplikat di file: {$name}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $this->seenNames[$name] = true;

            if (DeliveryMethod::where('name', $name)->exists()) {
                $this->errors->add(self::SHEET, $rowNumber, "Nama sudah ada: {$name}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate(self::SHEET);
                $this->stats->addSample(self::SHEET, [
                    'name' => $name,
                    'description' => $description !== '' ? $description : null,
                ]);
                $this->context->deliveryMethods[$name] = true;
                continue;
            }

            DeliveryMethod::create([
                'name' => $name,
                'description' => $description !== '' ? $description : null,
            ]);
            $this->stats->addCreated(self::SHEET);
            $this->context->deliveryMethods[$name] = true;
        }
    }
}
