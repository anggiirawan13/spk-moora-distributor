<?php

namespace App\Imports\Sheets;

use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\PaymentTerm;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PaymentTermSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
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
                $this->errors->add('payment_terms', $rowNumber, 'Nama kosong');
                $this->stats->addSkipped('payment_terms');
                continue;
            }

            if (isset($this->seenNames[$name])) {
                $this->errors->add('payment_terms', $rowNumber, "Duplikat di file: {$name}");
                $this->stats->addSkipped('payment_terms');
                continue;
            }

            $this->seenNames[$name] = true;

            if (PaymentTerm::where('name', $name)->exists()) {
                $this->errors->add('payment_terms', $rowNumber, "Nama sudah ada: {$name}");
                $this->stats->addSkipped('payment_terms');
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate('payment_terms');
                $this->stats->addSample('payment_terms', [
                    'name' => $name,
                    'description' => $description !== '' ? $description : null,
                ]);
                continue;
            }

            PaymentTerm::create([
                'name' => $name,
                'description' => $description !== '' ? $description : null,
            ]);
            $this->stats->addCreated('payment_terms');
        }
    }
}
