<?php

namespace App\Imports\Sheets;

use App\Imports\ImportErrorBag;
use App\Imports\ImportContext;
use App\Imports\ImportStats;
use App\Models\BusinessScale;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BusinessScaleSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private const SHEET = 'Skala_Bisnis';
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

            if (BusinessScale::where('name', $name)->exists()) {
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
                $this->context->businessScales[$name] = true;
                continue;
            }

            BusinessScale::create([
                'name' => $name,
                'description' => $description !== '' ? $description : null,
            ]);
            $this->stats->addCreated(self::SHEET);
            $this->context->businessScales[$name] = true;
        }
    }
}
