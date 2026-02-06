<?php

namespace App\Imports\Sheets;

use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\Criteria;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CriteriaSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private array $seenCodes = [];

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
            $code = strtoupper(trim((string) ($row['code'] ?? '')));
            $name = trim((string) ($row['name'] ?? ''));
            $weight = trim((string) ($row['weight'] ?? ''));
            $attributeType = trim((string) ($row['attribute_type'] ?? ''));

            if ($code === '' || $name === '' || $weight === '' || $attributeType === '') {
                $this->errors->add('criterias', $rowNumber, 'Field wajib kosong (code, name, weight, attribute_type)');
                $this->stats->addSkipped('criterias');
                continue;
            }

            if (isset($this->seenCodes[$code])) {
                $this->errors->add('criterias', $rowNumber, "Duplikat di file: {$code}");
                $this->stats->addSkipped('criterias');
                continue;
            }

            $this->seenCodes[$code] = true;

            if (!is_numeric($weight) || (float) $weight <= 0) {
                $this->errors->add('criterias', $rowNumber, 'Weight harus angka > 0');
                $this->stats->addSkipped('criterias');
                continue;
            }

            if (!in_array($attributeType, ['Benefit', 'Cost'], true)) {
                $this->errors->add('criterias', $rowNumber, 'attribute_type harus Benefit atau Cost');
                $this->stats->addSkipped('criterias');
                continue;
            }

            if (Criteria::where('code', $code)->exists()) {
                $this->errors->add('criterias', $rowNumber, "Code sudah ada: {$code}");
                $this->stats->addSkipped('criterias');
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate('criterias');
                $this->stats->addSample('criterias', [
                    'code' => $code,
                    'name' => $name,
                    'weight' => (float) $weight,
                    'attribute_type' => $attributeType,
                ]);
                continue;
            }

            Criteria::create([
                'code' => $code,
                'name' => $name,
                'weight' => (float) $weight,
                'attribute_type' => $attributeType,
            ]);
            $this->stats->addCreated('criterias');
        }
    }
}
