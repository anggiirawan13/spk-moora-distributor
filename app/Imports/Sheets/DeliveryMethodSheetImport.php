<?php

namespace App\Imports\Sheets;

use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\DeliveryMethod;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DeliveryMethodSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private array $seenNames = [];

    public function __construct(
        private readonly ImportErrorBag $errors,
        private readonly ImportStats $stats,
        private readonly bool $dryRun
    )
    {
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $name = trim((string) ($row['name'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));

            if ($name === '') {
                $this->errors->add('delivery_methods', $rowNumber, 'Nama kosong');
                $this->stats->addSkipped('delivery_methods');
                continue;
            }

            if (isset($this->seenNames[$name])) {
                $this->errors->add('delivery_methods', $rowNumber, "Duplikat di file: {$name}");
                $this->stats->addSkipped('delivery_methods');
                continue;
            }

            $this->seenNames[$name] = true;

            if (DeliveryMethod::where('name', $name)->exists()) {
                $this->errors->add('delivery_methods', $rowNumber, "Nama sudah ada: {$name}");
                $this->stats->addSkipped('delivery_methods');
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate('delivery_methods');
                $this->stats->addSample('delivery_methods', [
                    'name' => $name,
                    'description' => $description !== '' ? $description : null,
                ]);
                continue;
            }

            DeliveryMethod::create([
                'name' => $name,
                'description' => $description !== '' ? $description : null,
            ]);
            $this->stats->addCreated('delivery_methods');
        }
    }
}
