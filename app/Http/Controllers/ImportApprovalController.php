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
use Illuminate\Database\Eloquent\Model;
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

    public function index(Request $request): View
    {
        $user = auth()->user();
        $selectedStatus = (string) $request->query('status', '');
        $selectedType = (string) $request->query('type', '');
        $selectedApprovalStatus = (string) $request->query('approval_status', '');

        $batches = ImportBatch::with('importedBy')
            ->latest()
            ->get()
            ->map(function (ImportBatch $batch) use ($user, $selectedStatus, $selectedType, $selectedApprovalStatus) {
                $items = $this->batchItems($batch, $user);
                if ($items->isEmpty()) {
                    return null;
                }

                if ($selectedType !== '') {
                    $items = $items->where('type', $selectedType)->values();
                }

                if ($selectedApprovalStatus !== '') {
                    $items = $items->where('stage_status_key', $selectedApprovalStatus)->values();
                }

                if ($items->isEmpty()) {
                    return null;
                }

                $batchStatus = $this->batchStatus($items);

                if ($selectedStatus !== '' && $batchStatus['key'] !== $selectedStatus) {
                    return null;
                }

                return [
                    'batch' => $batch,
                    'items' => $items,
                    'status' => $batchStatus,
                ];
            })
            ->filter()
            ->values();

        $typeOptions = collect(self::TYPES)
            ->map(fn (array $config, string $key) => ['value' => $key, 'label' => $config['label']])
            ->values();

        $statusOptions = $this->statusOptions($user);
        $approvalStatusOptions = $this->approvalStatusOptions($user);

        return view('import_approval.index', compact(
            'batches',
            'selectedStatus',
            'selectedType',
            'selectedApprovalStatus',
            'typeOptions',
            'statusOptions',
            'approvalStatusOptions'
        ));
    }

    public function approveBatchAdmin(ImportBatch $batch): RedirectResponse
    {
        $items = $this->batchItems($batch, auth()->user())->where('can_approve_admin', true);

        if ($items->isEmpty()) {
            return redirect($this->approvalRedirectUrl($batch->id))->with('error', 'Batch ini tidak punya data yang bisa di-approve admin.');
        }

        foreach ($items as $item) {
            $record = $this->findRecord($item['type'], $item['id']);
            $record->approveByAdmin(auth()->id());
        }

        $this->syncBatchState($batch);

        return redirect($this->approvalRedirectUrl($batch->id))->with('success', 'Semua data yang eligible dalam batch berhasil di-approve admin.');
    }

    public function approveBatchDirector(ImportBatch $batch): RedirectResponse
    {
        $items = $this->batchItems($batch, auth()->user())->where('can_approve_director', true);

        if ($items->isEmpty()) {
            return redirect($this->approvalRedirectUrl($batch->id))->with('error', 'Batch ini tidak punya data yang bisa di-approve Direktur Utama.');
        }

        foreach ($items as $item) {
            $record = $this->findRecord($item['type'], $item['id']);
            $record->approveByDirector(auth()->id());
        }

        $this->syncBatchState($batch);

        return redirect($this->approvalRedirectUrl($batch->id))->with('success', 'Semua data yang eligible dalam batch berhasil di-approve Direktur Utama.');
    }

    public function rejectBatchAdmin(Request $request, ImportBatch $batch): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $items = $this->batchItems($batch, auth()->user())->where('can_reject_admin', true);
        if ($items->isEmpty()) {
            return redirect($this->approvalRedirectUrl($batch->id))->with('error', 'Batch ini tidak punya data yang bisa di-reject admin.');
        }

        $reason = trim((string) $request->input('reason'));

        foreach ($items as $item) {
            $record = $this->findRecord($item['type'], $item['id']);
            $record->rejectByAdmin(auth()->id(), $reason);
        }

        $this->syncBatchState($batch);

        return redirect($this->approvalRedirectUrl($batch->id))->with('warning', 'Semua data yang eligible dalam batch berhasil ditolak admin.');
    }

    public function rejectBatchDirector(Request $request, ImportBatch $batch): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $items = $this->batchItems($batch, auth()->user())->where('can_reject_director', true);
        if ($items->isEmpty()) {
            return redirect($this->approvalRedirectUrl($batch->id))->with('error', 'Batch ini tidak punya data yang bisa di-reject Direktur Utama.');
        }

        $reason = trim((string) $request->input('reason'));

        foreach ($items as $item) {
            $record = $this->findRecord($item['type'], $item['id']);
            $record->rejectByDirector(auth()->id(), $reason);
        }

        $this->syncBatchState($batch);

        return redirect($this->approvalRedirectUrl($batch->id))->with('warning', 'Semua data yang eligible dalam batch berhasil ditolak Direktur Utama.');
    }

    public function approveItem(Request $request, string $type, int $id): RedirectResponse
    {
        $record = $this->findRecord($type, $id);

        if ((int) auth()->user()->is_admin === 1) {
            if ($record->admin_approval_status !== 'pending') {
                return redirect($this->approvalRedirectUrl((int) $request->input('batch'), (int) $request->input('item_page', 1)))->with('error', 'Data ini tidak bisa di-approve lagi.');
            }

            if ($error = $this->approvalDependencyError($type, $record, 'admin')) {
                return redirect($this->approvalRedirectUrl((int) $request->input('batch'), (int) $request->input('item_page', 1)))->with('error', $error);
            }

            $record->approveByAdmin(auth()->id());
        } else {
            if ($record->admin_approval_status !== 'approved' || $record->director_approval_status !== 'pending') {
                return redirect($this->approvalRedirectUrl((int) $request->input('batch'), (int) $request->input('item_page', 1)))->with('error', 'Data ini tidak bisa di-approve lagi.');
            }

            if ($error = $this->approvalDependencyError($type, $record, 'director')) {
                return redirect($this->approvalRedirectUrl((int) $request->input('batch'), (int) $request->input('item_page', 1)))->with('error', $error);
            }

            $record->approveByDirector(auth()->id());
        }

        $this->syncBatchState($record->importBatch);

        return redirect($this->approvalRedirectUrl($record->import_batch_id, (int) $request->input('item_page', 1)))->with('success', 'Data berhasil di-approve.');
    }

    public function rejectItem(Request $request, string $type, int $id): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $record = $this->findRecord($type, $id);
        $reason = trim((string) $request->input('reason'));

        if ((int) auth()->user()->is_admin === 1) {
            if ($record->admin_approval_status !== 'pending') {
                return redirect($this->approvalRedirectUrl((int) $request->input('batch'), (int) $request->input('item_page', 1)))->with('error', 'Data ini tidak bisa di-reject lagi.');
            }

            $record->rejectByAdmin(auth()->id(), $reason);
        } else {
            if ($record->admin_approval_status !== 'approved' || $record->director_approval_status !== 'pending') {
                return redirect($this->approvalRedirectUrl((int) $request->input('batch'), (int) $request->input('item_page', 1)))->with('error', 'Data ini tidak bisa di-reject lagi.');
            }

            $record->rejectByDirector(auth()->id(), $reason);
        }

        $this->syncBatchState($record->importBatch);

        return redirect($this->approvalRedirectUrl($record->import_batch_id, (int) $request->input('item_page', 1)))->with('warning', 'Data ditolak dan alasan sudah disimpan.');
    }

    private function batchItems(ImportBatch $batch, $user): Collection
    {
        if ($batch->importedBy && (int) $batch->importedBy->is_admin === 1) {
            return collect();
        }

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

        $items = $items->sortBy(['type_label', 'name'])->values();

        return $items->map(function (array $item, int $index) {
            $record = $this->findRecord($item['type'], $item['id']);
            $item['edit_url'] = $record && $record->can_be_edited_by_current_user
                ? $this->editUrl($item['type'], $record, [
                    'batch' => request()->query('batch', $record->import_batch_id),
                    'item' => $item['id'],
                    'item_page' => (int) floor($index / 5) + 1,
                ])
                : null;

            return $item;
        })->values();
    }

    private function formatRecord(string $type, string $typeLabel, $record, $user): ?array
    {
        $isAdmin = (int) $user->is_admin === 1;
        $isDirector = $user->role === 'direktur_utama';

        $isVisible = $isAdmin
            || ($isDirector && $record->admin_approval_status === 'approved');

        if (!$isVisible) {
            return null;
        }

        $stageStatus = $this->stageStatus($record, $user);

        $canApproveAdmin = $isAdmin && $record->admin_approval_status === 'pending';
        $canApproveDirector = $isDirector
            && $record->admin_approval_status === 'approved'
            && $record->director_approval_status === 'pending';
        $canRejectAdmin = $canApproveAdmin;
        $canRejectDirector = $canApproveDirector;

        return [
            'type' => $type,
            'type_label' => $typeLabel,
            'id' => $record->id,
            'code' => $this->itemCode($type, $record),
            'name' => $this->itemName($type, $record),
            'approval_status_label' => $record->approval_status_label,
            'approval_reason' => $record->approval_reason,
            'edit_url' => $record->can_be_edited_by_current_user ? $this->editUrl($type, $record) : null,
            'stage_status_key' => $stageStatus['key'],
            'stage_status_label' => $stageStatus['label'],
            'can_approve_admin' => $canApproveAdmin,
            'can_approve_director' => $canApproveDirector,
            'can_reject_admin' => $canRejectAdmin,
            'can_reject_director' => $canRejectDirector,
        ];
    }

    private function stageStatus($record, $user): array
    {
        if ((int) $user->is_admin === 1) {
            return match ($record->admin_approval_status) {
                'approved' => ['key' => 'approved', 'label' => 'Disetujui Admin'],
                'rejected' => ['key' => 'rejected', 'label' => 'Ditolak Admin'],
                default => ['key' => 'pending', 'label' => 'Menunggu Admin'],
            };
        }

        return match ($record->director_approval_status) {
            'approved' => ['key' => 'approved', 'label' => 'Disetujui Direktur Utama'],
            'rejected' => ['key' => 'rejected', 'label' => 'Ditolak Direktur Utama'],
            default => ['key' => 'pending', 'label' => 'Menunggu Direktur Utama'],
        };
    }

    private function batchStatus(Collection $items): array
    {
        if ($items->contains(fn (array $item) => $item['stage_status_key'] === 'pending')) {
            return [
                'key' => 'pending',
                'label' => $items->firstWhere('stage_status_key', 'pending')['stage_status_label'] ?? 'Menunggu',
            ];
        }

        if ($items->contains(fn (array $item) => $item['stage_status_key'] === 'rejected')) {
            return ['key' => 'rejected', 'label' => $items->firstWhere('stage_status_key', 'rejected')['stage_status_label'] ?? 'Ditolak'];
        }

        return ['key' => 'approved', 'label' => $items->firstWhere('stage_status_key', 'approved')['stage_status_label'] ?? 'Disetujui'];
    }

    private function statusOptions($user): array
    {
        return (int) $user->is_admin === 1
            ? [
                ['value' => 'pending', 'label' => 'Menunggu Admin'],
                ['value' => 'approved', 'label' => 'Disetujui Admin'],
                ['value' => 'rejected', 'label' => 'Ditolak Admin'],
            ]
            : [
                ['value' => 'pending', 'label' => 'Menunggu Direktur Utama'],
                ['value' => 'approved', 'label' => 'Disetujui Direktur Utama'],
                ['value' => 'rejected', 'label' => 'Ditolak Direktur Utama'],
            ];
    }

    private function approvalStatusOptions($user): array
    {
        return $this->statusOptions($user);
    }

    private function itemCode(string $type, $record): string
    {
        return match ($type) {
            'business_scale', 'delivery_method', 'payment_term', 'product', 'criteria', 'sub_criteria', 'distributor' => (string) ($record->code ?? '-'),
            'alternative' => (string) ($this->findDistributor($record->distributor_id)?->code ?? '-'),
            'distributor_product' => trim(($this->findDistributor($record->distributor_id)?->code ?? '-') . ' / ' . ($this->findProduct($record->product_id)?->code ?? '-')),
            'alternative_value' => (string) ($this->findSubCriteria($record->sub_criteria_id)?->code ?? '-'),
            default => '-',
        };
    }

    private function itemName(string $type, $record): string
    {
        return match ($type) {
            'business_scale', 'delivery_method', 'payment_term', 'product', 'criteria', 'sub_criteria', 'distributor' => (string) ($record->name ?? '-'),
            'alternative' => (string) ($this->findDistributor($record->distributor_id)?->name ?? 'Alternatif'),
            'distributor_product' => trim(($this->findDistributor($record->distributor_id)?->name ?? '-') . ' / ' . ($this->findProduct($record->product_id)?->name ?? '-')),
            'alternative_value' => trim(($this->findSubCriteria($record->sub_criteria_id)?->name ?? '-') . ' = ' . ($record->value ?? 0)),
            default => '-',
        };
    }

    private function findDistributor(?int $id): ?Distributor
    {
        return $id ? Distributor::query()->find($id) : null;
    }

    private function findProduct(?int $id): ?Product
    {
        return $id ? Product::query()->find($id) : null;
    }

    private function findSubCriteria(?int $id): ?SubCriteria
    {
        return $id ? SubCriteria::query()->find($id) : null;
    }

    private function editUrl(string $type, $record, array $approvalContext = []): ?string
    {
        $baseUrl = match ($type) {
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

        if (!$baseUrl) {
            return null;
        }

        if ($approvalContext === []) {
            return $baseUrl;
        }

        $query = array_filter([
            'return_to' => 'import-approval',
            'approval_batch' => $approvalContext['batch'] ?? null,
            'approval_item' => $approvalContext['item'] ?? null,
            'approval_item_page' => $approvalContext['item_page'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');

        return $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . http_build_query($query);
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

    private function approvalRedirectUrl(?int $batchId = null, ?int $itemPage = null): string
    {
        return route('import.approvals.index', array_filter([
            'batch' => $batchId,
            'item_page' => $itemPage,
        ], fn ($value) => $value !== null && $value !== ''));
    }

    private function approvalDependencyError(string $type, Model $record, string $stage): ?string
    {
        $dependencies = $this->approvalDependencies($type, $record);

        foreach ($dependencies as $dependency) {
            if (!$dependency['model']) {
                return sprintf('%s tidak bisa di-approve karena %s belum tersedia.', $this->recordLabel($type, $record), $dependency['label']);
            }

            if (!$this->isDependencyApproved($dependency['model'], $stage)) {
                return sprintf('%s tidak bisa di-approve karena %s belum disetujui pada tahap ini.', $this->recordLabel($type, $record), $dependency['label']);
            }
        }

        return null;
    }

    private function approvalDependencies(string $type, Model $record): array
    {
        return match ($type) {
            'distributor' => [
                ['label' => 'Termin Pembayaran', 'model' => PaymentTerm::query()->find($record->payment_term_id)],
                ['label' => 'Metode Pengiriman', 'model' => DeliveryMethod::query()->find($record->delivery_method_id)],
                ['label' => 'Skala Bisnis', 'model' => BusinessScale::query()->find($record->business_scale_id)],
            ],
            'sub_criteria' => [
                ['label' => 'Kriteria', 'model' => Criteria::query()->find($record->criteria_id)],
            ],
            'alternative' => [
                ['label' => 'Distributor', 'model' => Distributor::query()->find($record->distributor_id)],
            ],
            'distributor_product' => [
                ['label' => 'Distributor', 'model' => Distributor::query()->find($record->distributor_id)],
                ['label' => 'Produk', 'model' => Product::query()->find($record->product_id)],
            ],
            'alternative_value' => [
                ['label' => 'Alternatif', 'model' => Alternative::query()->find($record->alternative_id)],
                ['label' => 'Sub Kriteria', 'model' => SubCriteria::query()->find($record->sub_criteria_id)],
            ],
            default => [],
        };
    }

    private function isDependencyApproved(Model $model, string $stage): bool
    {
        if (!$model->import_batch_id) {
            return true;
        }

        if ($stage === 'admin') {
            return $model->admin_approval_status === 'approved';
        }

        return $model->director_approval_status === 'approved';
    }

    private function recordLabel(string $type, Model $record): string
    {
        $typeLabel = self::TYPES[$type]['label'] ?? 'Data';
        $name = $this->itemName($type, $record);

        return trim($typeLabel . ' ' . ($name !== '' && $name !== '-' ? '"' . $name . '"' : ''));
    }
}
