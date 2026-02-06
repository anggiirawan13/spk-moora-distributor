<?php

namespace App\Imports\Sheets;

use App\Imports\ImportContext;
use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Criteria;
use App\Models\Distributor;
use App\Models\SubCriteria;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlternativeSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private const SHEET = 'Alternatif';
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

        $blockedDistributors = [];
        $createdAlternatives = [];
        $createdAltValues = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $distCode = strtoupper(trim((string) ($row['code'] ?? $row['code'] ?? '')));
            $criteriaCode = strtoupper(trim((string) ($row['criteria_code'] ?? '')));
            $subCriteriaCode = strtoupper(trim((string) ($row['sub_criteria_code'] ?? '')));

            if ($distCode === '' || $criteriaCode === '' || $subCriteriaCode === '') {
                $this->errors->add(self::SHEET, $rowNumber, 'Field wajib kosong (code, criteria_code, sub_criteria_code)');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $comboKey = $distCode . '|' . $criteriaCode . '|' . $subCriteriaCode;
            if (isset($this->seenCombos[$comboKey])) {
                $this->errors->add(self::SHEET, $rowNumber, "Duplikat di file: {$distCode} - {$criteriaCode} - {$subCriteriaCode}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $this->seenCombos[$comboKey] = true;

            if (isset($blockedDistributors[$distCode])) {
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $distributor = Distributor::where('code', $distCode)->first();
            if (!$distributor) {
                if ($this->dryRun && isset($this->context->distributors[$distCode])) {
                    $distributor = (object) ['id' => 0];
                } else {
                    $this->errors->add(self::SHEET, $rowNumber, "Distributor code tidak ditemukan: {$distCode}");
                    $blockedDistributors[$distCode] = true;
                    $this->stats->addSkipped(self::SHEET);
                    continue;
                }
            }

            if ($distributor->id !== 0 && !isset($createdAlternatives[$distCode])) {
                $existingAlternative = Alternative::where('distributor_id', $distributor->id)->first();
                if ($existingAlternative) {
                    $this->errors->add(self::SHEET, $rowNumber, "Alternative sudah ada untuk distributor {$distCode}");
                    $blockedDistributors[$distCode] = true;
                    $this->stats->addSkipped(self::SHEET);
                    continue;
                }
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

            $subCriteria = SubCriteria::where('criteria_id', $criteria->id)
                ->where('code', $subCriteriaCode)
                ->first();

            if (!$subCriteria) {
                if (!$this->dryRun || !isset($this->context->subCriteriaCodes[$criteriaCode][$subCriteriaCode])) {
                    $this->errors->add(self::SHEET, $rowNumber, "Sub kriteria tidak ditemukan: {$criteriaCode} - {$subCriteriaCode}");
                    $this->stats->addSkipped(self::SHEET);
                    continue;
                }
                $subCriteria = (object) ['id' => 0, 'value' => 0];
            }

            if (!isset($createdAlternatives[$distCode])) {
                if ($this->dryRun) {
                    $createdAlternatives[$distCode] = true;
                    $this->stats->addSample(self::SHEET, [
                        'code' => $distCode,
                        'criteria_code' => $criteriaCode,
                        'sub_criteria_code' => $subCriteriaCode,
                    ]);
                } else {
                    $createdAlternatives[$distCode] = Alternative::create([
                        'distributor_id' => $distributor->id,
                    ]);
                }
            }

            $alt = $createdAlternatives[$distCode];
            $valueKey = ($this->dryRun ? $distCode : $alt->id) . ':' . ($this->dryRun ? $subCriteriaCode : $subCriteria->id);

            if (isset($createdAltValues[$valueKey])) {
                $this->errors->add(self::SHEET, $rowNumber, "Duplikat mapping: {$distCode} - {$criteriaCode} - {$subCriteriaCode}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate(self::SHEET);
            } else {
                AlternativeValue::create([
                    'alternative_id' => $alt->id,
                    'sub_criteria_id' => $subCriteria->id,
                    'value' => $subCriteria->value ?? 0,
                ]);
                $this->stats->addCreated(self::SHEET);
            }

            $createdAltValues[$valueKey] = true;
        }
    }
}
