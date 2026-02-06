<?php

namespace App\Imports\Sheets;

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
        $blockedDistributors = [];
        $createdAlternatives = [];
        $createdAltValues = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $distCode = strtoupper(trim((string) ($row['code'] ?? $row['dist_code'] ?? '')));
            $criteriaCode = strtoupper(trim((string) ($row['criteria_code'] ?? '')));
            $subCriteriaName = trim((string) ($row['sub_criteria_name'] ?? ''));

            if ($distCode === '' || $criteriaCode === '' || $subCriteriaName === '') {
                $this->errors->add('alternatives', $rowNumber, 'Field wajib kosong (code, criteria_code, sub_criteria_name)');
                $this->stats->addSkipped('alternatives');
                continue;
            }

            $comboKey = $distCode . '|' . $criteriaCode . '|' . $subCriteriaName;
            if (isset($this->seenCombos[$comboKey])) {
                $this->errors->add('alternatives', $rowNumber, "Duplikat di file: {$distCode} - {$criteriaCode} - {$subCriteriaName}");
                $this->stats->addSkipped('alternatives');
                continue;
            }

            $this->seenCombos[$comboKey] = true;

            if (isset($blockedDistributors[$distCode])) {
                continue;
            }

            $distributor = Distributor::where('dist_code', $distCode)->first();
            if (!$distributor) {
                $this->errors->add('alternatives', $rowNumber, "Distributor code tidak ditemukan: {$distCode}");
                $blockedDistributors[$distCode] = true;
                $this->stats->addSkipped('alternatives');
                continue;
            }

            $existingAlternative = Alternative::where('distributor_id', $distributor->id)->first();
            if ($existingAlternative) {
                $this->errors->add('alternatives', $rowNumber, "Alternative sudah ada untuk distributor {$distCode}");
                $blockedDistributors[$distCode] = true;
                $this->stats->addSkipped('alternatives');
                continue;
            }

            $criteria = Criteria::where('code', $criteriaCode)->first();
            if (!$criteria) {
                $this->errors->add('alternatives', $rowNumber, "Criteria code tidak ditemukan: {$criteriaCode}");
                $this->stats->addSkipped('alternatives');
                continue;
            }

            $subCriteria = SubCriteria::where('criteria_id', $criteria->id)
                ->where('name', $subCriteriaName)
                ->first();

            if (!$subCriteria) {
                $this->errors->add('alternatives', $rowNumber, "Sub kriteria tidak ditemukan: {$criteriaCode} - {$subCriteriaName}");
                $this->stats->addSkipped('alternatives');
                continue;
            }

            if (!isset($createdAlternatives[$distCode])) {
            if ($this->dryRun) {
                $createdAlternatives[$distCode] = true;
                $this->stats->addWouldCreate('alternatives');
                $this->stats->addSample('alternatives', [
                    'code' => $distCode,
                    'criteria_code' => $criteriaCode,
                    'sub_criteria_name' => $subCriteriaName,
                ]);
            } else {
                $createdAlternatives[$distCode] = Alternative::create([
                    'distributor_id' => $distributor->id,
                ]);
                $this->stats->addCreated('alternatives');
            }
            }

            $alt = $createdAlternatives[$distCode];
            $valueKey = ($this->dryRun ? $distCode : $alt->id) . ':' . $subCriteria->id;

            if (isset($createdAltValues[$valueKey])) {
                $this->errors->add('alternatives', $rowNumber, "Duplikat mapping: {$distCode} - {$criteriaCode} - {$subCriteriaName}");
                $this->stats->addSkipped('alternatives');
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate('alternatives');
            } else {
                AlternativeValue::create([
                    'alternative_id' => $alt->id,
                    'sub_criteria_id' => $subCriteria->id,
                    'value' => $subCriteria->value ?? 0,
                ]);
                $this->stats->addCreated('alternatives');
            }

            $createdAltValues[$valueKey] = true;
        }
    }
}
