<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\BusinessScale;
use App\Models\Criteria;
use App\Models\DeliveryMethod;
use App\Models\Distributor;
use App\Models\DistributorProduct;
use App\Models\ImportBatch;
use App\Models\PaymentTerm;
use App\Models\Product;
use App\Models\SubCriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ImportApprovalController extends Controller
{
    private const TYPES = [
        'business_scale' => ['label' => 'Skala Bisnis', 'model' => BusinessScale::class],
        'delivery_method' => ['label' => 'Metode Pengiriman', 'model' => DeliveryMethod::class],
        'payment_term' => ['label' => 'Termin Pembayaran', 'model' => PaymentTerm::class],
        'distributor' => ['label' => 'Distributor', 'model' => Distributor::class],
        'product' => ['label' => 'Produk', 'model' => Product::class],
        'distributor_product' => ['label' => 'Distributor Produk', 'model' => DistributorProduct::class],
        'criteria' => ['label' => 'Kriteria', 'model' => Criteria::class],
        'sub_criteria' => ['label' => 'Sub Kriteria', 'model' => SubCriteria::class],
        'alternative' => ['label' => 'Alternatif', 'model' => Alternative::class],
        'alternative_value' => ['label' => 'Nilai Alternatif', 'model' => AlternativeValue::class],
    ];

    public function index(): View
    {
        $user = auth()->user();

        $batches = ImportBatch::with('importedBy')
            ->latest()
            ->get()
            ->map(function (ImportBatch $batch) use ($user) {
                $items = $this->batchItems($batch, $user);
                if ($items->isEmpty()) {
                    return null;
                }

                return [
                    'batch' => $batch,
                    'items' => $items,
                ];
            })
            ->filter()
            ->values();

        return view('import_approval.index', compact('batches'));
    }

    public function approveBatchAdmin(ImportBatch $batch): RedirectResponse
    {
        $items = $this->batchItems($batch, auth()->user())->where('can_approve_admin', true);

        foreach ($items as $item) {
            $record = $this->findRecord($item['type'], $item['id']);
            $record->approveByAdmin(auth()->id());
        }

        $this->syncBatchState($batch);

        return redirect()->route('import.approvals.index')->with('success', 'Semua data yang eligible dalam batch berhasil di-approve admin.');
    }

    public function approveBatchDirector(ImportBatch $batch): RedirectResponse
    {
        $items = $this->batchItems($batch, auth()->user())->where('can_approve_director', true);

        foreach ($items as $item) {
            $record = $this->findRecord($item['type'], $item['id']);
            $record->approveByDirector(auth()->id());
        }

        $this->syncBatchState($batch);

        return redirect()->route('import.approvals.index')->with('success', 'Semua data yang eligible dalam batch berhasil di-approve Direktur Utama.');
    }

    public function approveItem(Request $request, string $type, int $id): RedirectResponse
    {
        $record = $this->findRecord($type, $id);

        if ((int) auth()->user()->is_admin === 1) {
            $record->approveByAdmin(auth()->id());
        } else {
            if ($record->admin_approval_status !== 'approved') {
                return redirect()->route('import.approvals.index')->with('error', 'Data belum disetujui admin.');
            }

            $record->approveByDirector(auth()->id());
        }

        $this->syncBatchState($record->importBatch);

        return redirect()->route('import.approvals.index')->with('success', 'Data berhasil di-approve.');
    }

    public function rejectItem(Request $request, string $type, int $id): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $record = $this->findRecord($type, $id);
        $reason = trim((string) $request->input('reason'));

        if ((int) auth()->user()->is_admin === 1) {
            $record->rejectByAdmin(auth()->id(), $reason);
        } else {
            if ($record->admin_approval_status !== 'approved') {
                return redirect()->route('import.approvals.index')->with('error', 'Data belum disetujui admin.');
            }

            $record->rejectByDirector(auth()->id(), $reason);
        }

        $this->syncBatchState($record->importBatch);

        return redirect()->route('import.approvals.index')->with('warning', 'Data ditolak dan alasan sudah disimpan.');
    }

    private function batchItems(ImportBatch $batch, $user): Collection
    {
        $items = collect();

        foreach (self::TYPES as $type => $config) {
            $modelClass = $config['model'];
            $records = $modelClass::query()
                ->where('import_batch_id', $batch->id)
                ->get();

            foreach ($records as $record) {
                $row = $this->formatRecord($type, $config['label'], $record, $user);
                if ($row) {
                    $items->push($row);
                }
            }
        }

        return $items->sortBy(['type_label', 'display_name'])->values();
    }

    private function formatRecord(string $type, string $typeLabel, $record, $user): ?array
    {
        $isAdmin = (int) $user->is_admin === 1;
        $isDirector = $user->role === 'direktur_utama';

        $canApproveAdmin = $isAdmin && $record->admin_approval_status !== 'approved';
        $canApproveDirector = $isDirector
            && $record->admin_approval_status === 'approved'
            && $record->director_approval_status !== 'approved';

        if (!$canApproveAdmin && !$canApproveDirector && !$isAdmin && !$isDirector) {
            return null;
        }

        if (!$canApproveAdmin && !$canApproveDirector) {
            return null;
        }

        return [
            'type' => $type,
            'type_label' => $typeLabel,
            'id' => $record->id,
            'display_name' => $this->displayName($type, $record),
            'description' => $this->displayDescription($type, $record),
            'approval_status_label' => $record->approval_status_label,
            'approval_reason' => $record->approval_reason,
            'edit_url' => $this->editUrl($type, $record),
            'can_approve_admin' => $canApproveAdmin,
            'can_approve_director' => $canApproveDirector,
        ];
    }

    private function displayName(string $type, $record): string
    {
        return match ($type) {
            'business_scale', 'delivery_method', 'payment_term', 'product', 'criteria', 'sub_criteria', 'distributor' => trim(($record->code ?? '-') . ' - ' . ($record->name ?? '-')),
            'alternative' => trim(($record->distributor?->code ?? '-') . ' - ' . ($record->distributor?->name ?? 'Alternatif')),
            'distributor_product' => trim(($record->distributor?->code ?? '-') . ' <-> ' . ($record->product?->code ?? '-')),
            'alternative_value' => trim(($record->alternative?->distributor?->code ?? '-') . ' - ' . ($record->subCriteria?->code ?? '-')),
            default => (string) $record->id,
        };
    }

    private function displayDescription(string $type, $record): string
    {
        return match ($type) {
            'distributor' => $record->email ?? '-',
            'product', 'business_scale', 'delivery_method', 'payment_term' => $record->description ?: '-',
            'criteria' => 'Bobot: ' . $record->weight . ', Atribut: ' . $record->attribute_type,
            'sub_criteria' => 'Nilai: ' . $record->value,
            'alternative' => 'Distributor: ' . ($record->distributor?->name ?? '-'),
            'distributor_product' => ($record->distributor?->name ?? '-') . ' dengan ' . ($record->product?->name ?? '-'),
            'alternative_value' => ($record->subCriteria?->name ?? '-') . ' = ' . ($record->value ?? 0),
            default => '-',
        };
    }

    private function editUrl(string $type, $record): ?string
    {
        return match ($type) {
            'business_scale' => route('business_scale.edit', $record->id),
            'delivery_method' => route('delivery_method.edit', $record->id),
            'payment_term' => route('payment_term.edit', $record->id),
            'distributor' => route('distributor.edit', $record->id),
            'product' => route('product.edit', $record->id),
            'criteria' => route('criteria.edit', $record->id),
            'sub_criteria' => route('subcriteria.edit', $record->id),
            'alternative' => route('alternative.edit', $record->id),
            'distributor_product' => $record->product_id ? route('product.edit', $record->product_id) : null,
            'alternative_value' => $record->alternative_id ? route('alternative.edit', $record->alternative_id) : null,
            default => null,
        };
    }

    private function findRecord(string $type, int $id)
    {
        abort_unless(isset(self::TYPES[$type]), 404);

        $modelClass = self::TYPES[$type]['model'];

        return $modelClass::query()->findOrFail($id);
    }

    private function syncBatchState(?ImportBatch $batch): void
    {
        if (!$batch) {
            return;
        }

        $allAdminApproved = true;
        $allDirectorApproved = true;

        foreach (self::TYPES as $config) {
            $modelClass = $config['model'];
            $records = $modelClass::query()
                ->where('import_batch_id', $batch->id)
                ->get(['admin_approval_status', 'director_approval_status']);

            foreach ($records as $record) {
                if ($record->admin_approval_status !== 'approved') {
                    $allAdminApproved = false;
                }

                if ($record->director_approval_status !== 'approved') {
                    $allDirectorApproved = false;
                }
            }
        }

        $batch->update([
            'admin_approved_at' => $allAdminApproved ? ($batch->admin_approved_at ?: now()) : null,
            'admin_approved_by' => $allAdminApproved ? ($batch->admin_approved_by ?: auth()->id()) : null,
            'director_approved_at' => $allDirectorApproved ? ($batch->director_approved_at ?: now()) : null,
            'director_approved_by' => $allDirectorApproved ? ($batch->director_approved_by ?: auth()->id()) : null,
        ]);
    }
}
