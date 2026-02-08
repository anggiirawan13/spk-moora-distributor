<?php

namespace App\Imports\Sheets;

use App\Imports\ImportContext;
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
    private const SHEET = 'Sub Kriteria';
    private array $seenCombos = [];

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
            $criteriaCode = strtoupper(trim((string) ($row['criteria_code'] ?? '')));
            $code = strtoupper(trim((string) ($row['code'] ?? '')));
            $name = trim((string) ($row['name'] ?? ''));
            $value = trim((string) ($row['value'] ?? ''));

            if ($criteriaCode === '' || $name === '' || $value === '') {
                $this->errors->add(self::SHEET, $rowNumber, 'Field wajib tidak boleh kosong (criteria_code, name, value)');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $comboKey = $criteriaCode . '|' . $name;
            if (isset($this->seenCombos[$comboKey])) {
                $this->errors->add(self::SHEET, $rowNumber, "Duplikat di file: {$criteriaCode} - {$name}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $this->seenCombos[$comboKey] = true;

            if ($code !== '') {
                $codeKey = 'code|' . $code;
                if (isset($this->seenCombos[$codeKey])) {
                    $this->errors->add(self::SHEET, $rowNumber, "Duplikat code di file: {$code}");
                    $this->stats->addSkipped(self::SHEET);
                    continue;
                }
                $this->seenCombos[$codeKey] = true;
            }

            if (!is_numeric($value) || (int) $value < 0) {
                $this->errors->add(self::SHEET, $rowNumber, 'Value harus angka >= 0');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $criteria = Criteria::where('code', $criteriaCode)->first();
            if (!$criteria) {
                if ($this->dryRun && isset($this->context->criterias[$criteriaCode])) {
                    $criteria = (object) ['id' => 0];
                } else {
                    $this->errors->add(self::SHEET, $rowNumber, "Criteria code tidak ditemukan: {$criteriaCode}");
                    $this->stats->addSkipped(self::SHEET);
                    continue;
                }
            }

            if ($code !== '' && SubCriteria::where('code', $code)->exists()) {
                $this->errors->add(self::SHEET, $rowNumber, "Code sub kriteria sudah ada: {$code}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if (SubCriteria::where('criteria_id', $criteria->id)->where('name', $name)->exists()) {
                $this->errors->add(self::SHEET, $rowNumber, "Sub kriteria sudah ada untuk {$criteriaCode}: {$name}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate(self::SHEET);
                $this->stats->addSample(self::SHEET, [
                    'criteria_code' => $criteriaCode,
                    'code' => $code !== '' ? $code : '(auto)',
                    'name' => $name,
                    'value' => (int) $value,
                ]);
                if ($code !== '') {
                    $this->context->subCriteriaCodes[$criteriaCode][$code] = true;
                }
                continue;
            }

            SubCriteria::create([
                'criteria_id' => $criteria->id,
                'code' => $code !== '' ? $code : null,
                'name' => $name,
                'value' => (int) $value,
            ]);
            $this->stats->addCreated(self::SHEET);
            if ($code !== '') {
                $this->context->subCriteriaCodes[$criteriaCode][$code] = true;
            }
        }
    }
}
