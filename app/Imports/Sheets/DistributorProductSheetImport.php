<?php

namespace App\Imports\Sheets;

use App\Imports\ImportContext;
use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\Distributor;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DistributorProductSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private const SHEET = 'Distributor Produk';
    private array $seenCombos = [];

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
            $distributorCode = strtoupper(trim((string) ($row['distributor_code'] ?? $row['distributor_code'] ?? '')));
            $productCode = strtoupper(trim((string) ($row['product_code'] ?? '')));

            if ($distributorCode === '' || $productCode === '') {
                $this->errors->add(self::SHEET, $rowNumber, 'Field wajib tidak boleh kosong (distributor_code, product_code)');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $comboKey = $distributorCode . '|' . $productCode;
            if (isset($this->seenCombos[$comboKey])) {
                $this->errors->add(self::SHEET, $rowNumber, "Duplikat di file: {$distributorCode} - {$productCode}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }
            $this->seenCombos[$comboKey] = true;

            $distributor = Distributor::where('code', $distributorCode)->first();
            if (!$distributor) {
                if (!$this->dryRun || !isset($this->context->distributors[$distributorCode])) {
                    $this->errors->add(self::SHEET, $rowNumber, "Distributor tidak ditemukan: {$distributorCode}");
                    $this->stats->addSkipped(self::SHEET);
                    continue;
                }
                $distributor = (object) ['id' => 0];
            }

            $product = Product::where('code', $productCode)->first();
            if (!$product) {
                if (!$this->dryRun || !isset($this->context->products[$productCode])) {
                    $this->errors->add(self::SHEET, $rowNumber, "Produk tidak ditemukan: {$productCode}");
                    $this->stats->addSkipped(self::SHEET);
                    continue;
                }
                $product = (object) ['id' => 0];
            }

            if ($distributor->id !== 0 && $product->id !== 0) {
                $exists = DB::table('distributor_product')
                    ->where('distributor_id', $distributor->id)
                    ->where('product_id', $product->id)
                    ->exists();

                if ($exists) {
                    $this->errors->add(self::SHEET, $rowNumber, "Mapping sudah ada: {$distributorCode} - {$productCode}");
                    $this->stats->addSkipped(self::SHEET);
                    continue;
                }
            }

            if ($this->dryRun) {
                $this->stats->addWouldCreate(self::SHEET);
                $this->stats->addSample(self::SHEET, [
                    'distributor_code' => $distributorCode,
                    'product_code' => $productCode,
                ]);
                $this->context->distributorProducts[$comboKey] = true;
                continue;
            }

            DB::table('distributor_product')->insert([
                'distributor_id' => $distributor->id,
                'product_id' => $product->id,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
            $this->stats->addCreated(self::SHEET);
            $this->context->distributorProducts[$comboKey] = true;
        }
    }
}
