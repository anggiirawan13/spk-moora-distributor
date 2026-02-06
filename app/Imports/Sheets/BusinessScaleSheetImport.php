<?php

namespace App\Imports\Sheets;

use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\BusinessScale;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BusinessScaleSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
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
                $this->errors->add('business_scales', $rowNumber, 'Nama kosong');
                $this->stats->addSkipped('business_scales');
                continue;
            }

            if (isset($this->seenNames[$name])) {
                $this->errors->add('business_scales', $rowNumber, "Duplikat di file: {$name}");
                $this->stats->addSkipped('business_scales');
                continue;
            }

            $this->seenNames[$name] = true;

            if (BusinessScale::where('name', $name)->exists()) {
                $this->errors->add('business_scales', $rowNumber, "Nama sudah ada: {$name}");
                $this->stats->addSkipped('business_scales');
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate('business_scales');
                $this->stats->addSample('business_scales', [
                    'name' => $name,
                    'description' => $description !== '' ? $description : null,
                ]);
                continue;
            }

            BusinessScale::create([
                'name' => $name,
                'description' => $description !== '' ? $description : null,
            ]);
            $this->stats->addCreated('business_scales');
        }
    }
}
