<?php

namespace App\Imports\Sheets;

use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Models\BusinessScale;
use App\Models\DeliveryMethod;
use App\Models\Distributor;
use App\Models\PaymentTerm;
use App\Services\NpwpValidationService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DistributorSheetImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
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
        $npwpValidator = new NpwpValidationService();

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $distCodeRaw = (string) ($row['code'] ?? $row['dist_code'] ?? '');
            $distCode = strtoupper(trim($distCodeRaw));
            $name = trim((string) ($row['name'] ?? ''));
            $npwpRaw = (string) ($row['npwp'] ?? '');
            $npwp = preg_replace('/\D+/', '', $npwpRaw);
            $email = trim((string) ($row['email'] ?? ''));
            $phone = trim((string) ($row['phone'] ?? ''));
            $address = trim((string) ($row['address'] ?? ''));
            $paymentTermName = trim((string) ($row['payment_term'] ?? ''));
            $deliveryMethodName = trim((string) ($row['delivery_method'] ?? ''));
            $businessScaleName = trim((string) ($row['business_scale'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));
            $isActiveRaw = strtolower(trim((string) ($row['is_active'] ?? '1')));

            if ($distCode === '' || $name === '' || $npwp === '' || $email === '' || $phone === '' || $address === '') {
                $this->errors->add('distributors', $rowNumber, 'Field wajib kosong (code, name, npwp, email, phone, address)');
                $this->stats->addSkipped('distributors');
                continue;
            }

            if (isset($this->seenCodes[$distCode])) {
                $this->errors->add('distributors', $rowNumber, "Duplikat di file: {$distCode}");
                $this->stats->addSkipped('distributors');
                continue;
            }

            $this->seenCodes[$distCode] = true;

            if (preg_match('/[A-Za-z]/', $npwpRaw)) {
                $this->errors->add('distributors', $rowNumber, 'NPWP hanya boleh angka/tanda baca');
                $this->stats->addSkipped('distributors');
                continue;
            }

            if (strlen($npwp) !== 15) {
                $this->errors->add('distributors', $rowNumber, 'NPWP harus 15 digit angka');
                $this->stats->addSkipped('distributors');
                continue;
            }

            if (!$npwpValidator->isValid($npwp)) {
                $this->errors->add('distributors', $rowNumber, 'NPWP tidak valid (cek ke API)');
                $this->stats->addSkipped('distributors');
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors->add('distributors', $rowNumber, 'Email tidak valid');
                $this->stats->addSkipped('distributors');
                continue;
            }

            $phoneDigits = preg_replace('/\\D+/', '', $phone);
            $startsValid = str_starts_with($phoneDigits, '0') || str_starts_with($phoneDigits, '62');
            if ($phoneDigits === '' || strlen($phoneDigits) < 8 || strlen($phoneDigits) > 15 || !$startsValid) {
                $this->errors->add('distributors', $rowNumber, 'Telepon harus 8-15 digit dan diawali 0 atau 62');
                $this->stats->addSkipped('distributors');
                continue;
            }

            if (Distributor::where('dist_code', $distCode)->exists()) {
                $this->errors->add('distributors', $rowNumber, "Kode distributor sudah ada: {$distCode}");
                $this->stats->addSkipped('distributors');
                continue;
            }

            if (Distributor::where('npwp', $npwp)->exists()) {
                $this->errors->add('distributors', $rowNumber, "NPWP sudah ada: {$npwp}");
                $this->stats->addSkipped('distributors');
                continue;
            }

            $paymentTerm = PaymentTerm::where('name', $paymentTermName)->first();
            $deliveryMethod = DeliveryMethod::where('name', $deliveryMethodName)->first();
            $businessScale = BusinessScale::where('name', $businessScaleName)->first();

            if (!$paymentTerm || !$deliveryMethod || !$businessScale) {
                $this->errors->add('distributors', $rowNumber, 'Referensi payment_term/delivery_method/business_scale tidak ditemukan');
                $this->stats->addSkipped('distributors');
                continue;
            }

            $isActive = in_array($isActiveRaw, ['1', 'true', 'ya', 'yes'], true) ? 1 : 0;

            if ($this->dryRun) {
                $this->stats->addWouldCreate('distributors');
                $this->stats->addSample('distributors', [
                    'code' => $distCode,
                    'name' => $name,
                    'npwp' => $npwp,
                    'email' => $email,
                    'phone' => $phoneDigits,
                    'address' => $address,
                    'payment_term' => $paymentTermName,
                    'delivery_method' => $deliveryMethodName,
                    'business_scale' => $businessScaleName,
                    'description' => $description !== '' ? $description : null,
                    'is_active' => $isActive,
                ]);
                continue;
            }

            Distributor::create([
                'dist_code' => $distCode,
                'name' => $name,
                'npwp' => $npwp,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'payment_term_id' => $paymentTerm->id,
                'delivery_method_id' => $deliveryMethod->id,
                'business_scale_id' => $businessScale->id,
                'description' => $description !== '' ? $description : null,
                'is_active' => $isActive,
            ]);
            $this->stats->addCreated('distributors');
        }
    }
}
