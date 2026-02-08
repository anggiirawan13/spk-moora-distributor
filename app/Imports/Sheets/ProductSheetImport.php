<?php

namespace App\Imports\Sheets;

use App\Imports\ImportContext;
use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private const SHEET = 'Produk';
    private array $seenCodes = [];

    public function __construct(
        private readonly ImportErrorBag $errors,
        private readonly ImportStats $stats,
        private readonly ImportContext $context,
        private readonly bool $dryRun
    ) {
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
            $description = trim((string) ($row['description'] ?? ''));

            if ($code === '' || $name === '') {
                $this->errors->add(self::SHEET, $rowNumber, 'Field wajib tidak boleh kosong (code, name)');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if (isset($this->seenCodes[$code])) {
                $this->errors->add(self::SHEET, $rowNumber, "Duplikat di file: {$code}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }
            $this->seenCodes[$code] = true;

            if (Product::where('code', $code)->exists()) {
                $this->errors->add(self::SHEET, $rowNumber, "Code sudah ada: {$code}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate(self::SHEET);
                $this->stats->addSample(self::SHEET, [
                    'code' => $code,
                    'name' => $name,
                    'description' => $description !== '' ? $description : null,
                ]);
                $this->context->products[$code] = $code;
                continue;
            }

            Product::create([
                'code' => $code,
                'name' => $name,
                'description' => $description !== '' ? $description : null,
            ]);
            $this->stats->addCreated(self::SHEET);
            $this->context->products[$code] = $code;
        }
    }
}
