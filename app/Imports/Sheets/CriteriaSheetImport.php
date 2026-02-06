<?php

namespace App\Imports\Sheets;

use App\Imports\ImportContext;
use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\Criteria;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CriteriaSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private const SHEET = 'Kriteria';
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
            $code = strtoupper(trim((string) ($row['code'] ?? '')));
            $name = trim((string) ($row['name'] ?? ''));
            $weight = trim((string) ($row['weight'] ?? ''));
            $attributeType = trim((string) ($row['attribute_type'] ?? ''));

            if ($code === '' || $name === '' || $weight === '' || $attributeType === '') {
                $this->errors->add(self::SHEET, $rowNumber, 'Field wajib kosong (code, name, weight, attribute_type)');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if (isset($this->seenCodes[$code])) {
                $this->errors->add(self::SHEET, $rowNumber, "Duplikat di file: {$code}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $this->seenCodes[$code] = true;

            if (!is_numeric($weight) || (float) $weight <= 0) {
                $this->errors->add(self::SHEET, $rowNumber, 'Weight harus angka > 0');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if (!in_array($attributeType, ['Benefit', 'Cost'], true)) {
                $this->errors->add(self::SHEET, $rowNumber, 'attribute_type harus Benefit atau Cost');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if (Criteria::where('code', $code)->exists()) {
                $this->errors->add(self::SHEET, $rowNumber, "Code sudah ada: {$code}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate(self::SHEET);
                $this->stats->addSample(self::SHEET, [
                    'code' => $code,
                    'name' => $name,
                    'weight' => (float) $weight,
                    'attribute_type' => $attributeType,
                ]);
                $this->context->criterias[$code] = true;
                continue;
            }

            Criteria::create([
                'code' => $code,
                'name' => $name,
                'weight' => (float) $weight,
                'attribute_type' => $attributeType,
            ]);
            $this->stats->addCreated(self::SHEET);
            $this->context->criterias[$code] = true;
        }
    }
}
