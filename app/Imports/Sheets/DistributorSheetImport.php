<?php

namespace App\Imports\Sheets;

use App\Imports\ImportContext;
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
    private const SHEET = 'Distributor';
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

        $npwpValidator = new NpwpValidationService();

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $distCodeRaw = (string) ($row['code'] ?? $row['code'] ?? '');
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

            if ($distCode === '' || $name === '' || $email === '' || $phone === '' || $address === '') {
                $this->errors->add(self::SHEET, $rowNumber, 'Field wajib kosong (code, name, email, phone, address)');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if (isset($this->seenCodes[$distCode])) {
                $this->errors->add(self::SHEET, $rowNumber, "Duplikat di file: {$distCode}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $this->seenCodes[$distCode] = true;

            if ($npwpRaw !== '' && preg_match('/[A-Za-z]/', $npwpRaw)) {
                $this->errors->add(self::SHEET, $rowNumber, 'NPWP hanya boleh angka/tanda baca');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if ($npwp !== '' && strlen($npwp) !== 15) {
                $this->errors->add(self::SHEET, $rowNumber, 'NPWP harus 15 digit angka');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if ($npwp !== '') {
                $npwpResult = $npwpValidator->validate($npwp);
                if (!$npwpResult['valid']) {
                    $reason = $npwpResult['message'] ?? 'NPWP tidak valid';
                    $this->errors->add(self::SHEET, $rowNumber, "NPWP tidak valid: {$reason}");
                    $this->stats->addSkipped(self::SHEET);
                    continue;
                }
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors->add(self::SHEET, $rowNumber, 'Email tidak valid');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $phoneDigits = preg_replace('/\\D+/', '', $phone);
            $startsValid = str_starts_with($phoneDigits, '0') || str_starts_with($phoneDigits, '62');
            if ($phoneDigits === '' || strlen($phoneDigits) < 8 || strlen($phoneDigits) > 15 || !$startsValid) {
                $this->errors->add(self::SHEET, $rowNumber, 'Telepon harus 8-15 digit dan diawali 0 atau 62');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if (Distributor::where('code', $distCode)->exists()) {
                $this->errors->add(self::SHEET, $rowNumber, "Kode distributor sudah ada: {$distCode}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            if ($npwp !== '' && Distributor::where('npwp', $npwp)->exists()) {
                $this->errors->add(self::SHEET, $rowNumber, "NPWP sudah ada: {$npwp}");
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $paymentTerm = PaymentTerm::where('name', $paymentTermName)->first();
            $deliveryMethod = DeliveryMethod::where('name', $deliveryMethodName)->first();
            $businessScale = BusinessScale::where('name', $businessScaleName)->first();

            if ($this->dryRun) {
                $paymentTermOk = $paymentTerm || isset($this->context->paymentTerms[$paymentTermName]);
                $deliveryMethodOk = $deliveryMethod || isset($this->context->deliveryMethods[$deliveryMethodName]);
                $businessScaleOk = $businessScale || isset($this->context->businessScales[$businessScaleName]);
            } else {
                $paymentTermOk = (bool) $paymentTerm;
                $deliveryMethodOk = (bool) $deliveryMethod;
                $businessScaleOk = (bool) $businessScale;
            }

            if (!$paymentTermOk || !$deliveryMethodOk || !$businessScaleOk) {
                $this->errors->add(self::SHEET, $rowNumber, 'Referensi payment_term/delivery_method/business_scale tidak ditemukan');
                $this->stats->addSkipped(self::SHEET);
                continue;
            }

            $isActive = in_array($isActiveRaw, ['1', 'true', 'ya', 'yes'], true) ? 1 : 0;

            if ($this->dryRun) {
                $this->stats->addWouldCreate(self::SHEET);
                $this->stats->addSample(self::SHEET, [
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
                $this->context->distributors[$distCode] = true;
                continue;
            }

            Distributor::create([
                'code' => $distCode,
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
            $this->stats->addCreated(self::SHEET);
            $this->context->distributors[$distCode] = true;
        }
    }
}
