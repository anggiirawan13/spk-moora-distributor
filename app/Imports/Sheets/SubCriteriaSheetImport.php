<?php

namespace App\Imports\Sheets;

use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\Criteria;
use App\Models\SubCriteria;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubCriteriaSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private array $seenCombos = [];

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
            $criteriaCode = strtoupper(trim((string) ($row['criteria_code'] ?? '')));
            $name = trim((string) ($row['name'] ?? ''));
            $value = trim((string) ($row['value'] ?? ''));

            if ($criteriaCode === '' || $name === '' || $value === '') {
                $this->errors->add('sub_criteria', $rowNumber, 'Field wajib kosong (criteria_code, name, value)');
                $this->stats->addSkipped('sub_criteria');
                continue;
            }

            $comboKey = $criteriaCode . '|' . $name;
            if (isset($this->seenCombos[$comboKey])) {
                $this->errors->add('sub_criteria', $rowNumber, "Duplikat di file: {$criteriaCode} - {$name}");
                $this->stats->addSkipped('sub_criteria');
                continue;
            }

            $this->seenCombos[$comboKey] = true;

            if (!is_numeric($value) || (int) $value < 0) {
                $this->errors->add('sub_criteria', $rowNumber, 'Value harus angka >= 0');
                $this->stats->addSkipped('sub_criteria');
                continue;
            }

            $criteria = Criteria::where('code', $criteriaCode)->first();
            if (!$criteria) {
                $this->errors->add('sub_criteria', $rowNumber, "Criteria code tidak ditemukan: {$criteriaCode}");
                $this->stats->addSkipped('sub_criteria');
                continue;
            }

            if (SubCriteria::where('criteria_id', $criteria->id)->where('name', $name)->exists()) {
                $this->errors->add('sub_criteria', $rowNumber, "Sub kriteria sudah ada untuk {$criteriaCode}: {$name}");
                $this->stats->addSkipped('sub_criteria');
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate('sub_criteria');
                $this->stats->addSample('sub_criteria', [
                    'criteria_code' => $criteriaCode,
                    'name' => $name,
                    'value' => (int) $value,
                ]);
                continue;
            }

            SubCriteria::create([
                'criteria_id' => $criteria->id,
                'name' => $name,
                'value' => (int) $value,
            ]);
            $this->stats->addCreated('sub_criteria');
        }
    }
}
