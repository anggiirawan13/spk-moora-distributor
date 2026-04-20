<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\BusinessScale;
use App\Models\Criteria;
use App\Models\DeliveryMethod;
use App\Models\Distributor;
use App\Models\DistributorProduct;
use App\Imports\ImportContext;
use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Imports\MasterImport;
use App\Exports\TemplateExport;
use App\Exports\SeederTemplateExport;
use App\Models\ImportBatch;
use App\Models\PaymentTerm;
use App\Models\Product;
use App\Models\SubCriteria;
use App\Support\ImportBatchState;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ImportController extends Controller
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
        return view('import.index');
    }

    public function history(Request $request): View
    {
        $user = auth()->user();
        $search = trim((string) $request->query('search', ''));
        $selectedStatus = (string) $request->query('status', '');
        $selectedType = (string) $request->query('type', '');
        $selectedApprovalStatus = (string) $request->query('approval_status', '');

        $batchQuery = ImportBatch::with('importedBy')
            ->where('imported_by', $user->id)
            ->latest();

        if ($search !== '') {
            $batchQuery->where(function ($query) use ($search, $user) {
                $query->where('original_file_name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');

            });
        }

        $importBatches = $batchQuery->paginate(10)->withQueryString();

        $importBatches->setCollection(
            $importBatches->getCollection()
                ->map(function (ImportBatch $batch) use ($user, $selectedStatus, $selectedType, $selectedApprovalStatus) {
                    $items = $this->batchItems($batch, $user);
                    if ($selectedType !== '') {
                        $items = $items->where('type', $selectedType)->values();
                    }

                    if ($selectedApprovalStatus !== '') {
                        $items = $items->where('approval_status_key', $selectedApprovalStatus)->values();
                    }

                    if ($items->isEmpty()) {
                        return null;
                    }

                    $summary = $this->batchSummary($items);
                    if ($selectedStatus !== '' && $summary['status_key'] !== $selectedStatus) {
                        return null;
                    }

                    return [
                        'batch' => $batch,
                        'items' => $items,
                        'summary' => $summary,
                    ];
                })
                ->filter(fn ($entry) => $entry !== null)
                ->values()
        );

        $typeOptions = collect(self::TYPES)
            ->map(fn (array $config, string $key) => ['value' => $key, 'label' => $config['label']])
            ->values();
        $statusOptions = [
            ['value' => 'pending', 'label' => 'Pending'],
            ['value' => 'approved', 'label' => 'Approved'],
            ['value' => 'rejected', 'label' => 'Reject'],
        ];
        $approvalStatusOptions = [
            ['value' => 'pending_admin', 'label' => 'Menunggu Admin'],
            ['value' => 'pending_director', 'label' => 'Menunggu Direktur Utama'],
            ['value' => 'approved', 'label' => 'Data Aktif'],
            ['value' => 'rejected_admin', 'label' => 'Ditolak Admin'],
            ['value' => 'rejected_director', 'label' => 'Ditolak Direktur Utama'],
        ];

        return view('import.history', compact(
            'importBatches',
            'search',
            'selectedStatus',
            'selectedType',
            'selectedApprovalStatus',
            'typeOptions',
            'statusOptions',
            'approvalStatusOptions'
        ));
    }

    public function preview(Request $request): View
    {
        abort(404);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $errors = new ImportErrorBag();
        $stats = new ImportStats();
        $context = new ImportContext();
        $importBatch = ImportBatch::create([
            'original_file_name' => $file->getClientOriginalName(),
            'imported_by' => auth()->id(),
            'admin_approved_at' => auth()->user()->is_admin == 1 ? now() : null,
            'admin_approved_by' => auth()->user()->is_admin == 1 ? auth()->id() : null,
            'director_approved_at' => auth()->user()->is_admin == 1 ? now() : null,
            'director_approved_by' => auth()->user()->is_admin == 1 ? auth()->id() : null,
        ]);
        $context->importBatchId = $importBatch->id;
        $import = new MasterImport($errors, $stats, $context, false);

        ImportBatchState::activate($importBatch->id);

        try {
            Excel::import($import, $file);
        } catch (Throwable $exception) {
            $errors->add('SYSTEM', 0, 'Import gagal diproses: ' . $exception->getMessage());
        } finally {
            ImportBatchState::clear();
        }

        $statsSummary = $stats->all();
        $importBatch->update([
            'stats' => $statsSummary,
        ]);
        $successMessage = auth()->user()->is_admin == 1
            ? 'Import berhasil. Data langsung aktif tanpa proses approval.'
            : 'Import berhasil dan menunggu persetujuan admin.';

        if ($errors->has()) {
            $errorList = $errors->all();
            $message = $errorList[0] ?? 'Terjadi error saat import.';
            $errorFileName = $this->storeErrorFile($errors, $file->getClientOriginalName());

            if (count($errorList) > 1) {
                $message .= ' Dan ' . (count($errorList) - 1) . ' error lainnya.';
            }

            return redirect()
                ->route('import.excel.index')
                ->with('error', $message)
                ->with('error_download_url', route('import.excel.errors', ['file' => $errorFileName]))
                ->with('error_download_name', $errorFileName);
        }

        return redirect()
            ->route('import.excel.index')
            ->with('success', $successMessage);
    }

    public function downloadErrors(string $file)
    {
        $path = 'import-errors/' . $file;

        if (!Storage::disk('local')->exists($path)) {
            return redirect()->back()->with('error', 'File error tidak ditemukan');
        }

        return Storage::disk('local')->download($path);
    }

    private function storeErrorFile(ImportErrorBag $errors, string $originalFileName): string
    {
        $baseName = pathinfo($originalFileName, PATHINFO_FILENAME);
        $sanitizedBaseName = preg_replace('/[^A-Za-z0-9\-_]/', '-', $baseName) ?: 'import';
        $fileName = $sanitizedBaseName . '-errors-' . now()->format('Ymd-His') . '.txt';

        Storage::disk('local')->put('import-errors/' . $fileName, $errors->toText());

        return $fileName;
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateExport(), 'template-import.xlsx');
    }

    public function downloadSeederTemplate()
    {
        $fileName = 'template-import-seeder.xlsx';
        $storedPath = 'templates/' . $fileName;

        Excel::store(new SeederTemplateExport(), $storedPath, 'local');

        return Storage::disk('local')->download($storedPath, $fileName);
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
                if ((int) $user->is_admin !== 1 && (int) $record->created_by !== (int) $user->id) {
                    continue;
                }

                $items->push([
                    'id' => $record->id,
                    'type' => $type,
                    'type_label' => $config['label'],
                    'code' => $this->itemCode($type, $record),
                    'name' => $this->itemName($type, $record),
                    'display_name' => $this->displayName($type, $record),
                    'description' => $this->displayDescription($type, $record),
                    'approval_status_label' => $record->approval_status_label,
                    'approval_status_key' => $this->approvalStatusKey($record),
                    'approval_reason' => $record->approval_reason,
                    'edit_url' => null,
                    'delete_url' => $this->canDeleteFromHistory($record) ? $this->deleteUrl($type, $record) : null,
                    'can_edit' => $record->can_be_edited_by_current_user,
                    'can_delete' => $this->canDeleteFromHistory($record),
                ]);
            }
        }

        $items = $items->sortBy(['type_label', 'display_name'])->values();

        return $items->map(function (array $item, int $index) use ($batch, $user) {
            $record = $this->findRecord($item['type'], $item['id']);
            $item['edit_url'] = $record && $this->canEditFromHistory($record, $user)
                ? $this->editUrl($item['type'], $record, [
                    'page' => request()->query('page', 1),
                    'search' => request()->query('search'),
                    'batch' => $batch->id,
                    'item' => $item['id'],
                    'item_page' => (int) floor($index / 5) + 1,
                ])
                : null;

            return $item;
        })->values();
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

    private function batchSummary(Collection $items): array
    {
        $pending = $items->where('approval_status_label', 'Menunggu Admin')->count()
            + $items->where('approval_status_label', 'Menunggu Direktur Utama')->count();
        $approved = $items->where('approval_status_label', 'Data Aktif')->count();
        $rejected = $items->filter(fn ($item) => str_contains($item['approval_status_label'], 'Ditolak'))->count();

        return [
            'total' => $items->count(),
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'status_key' => $pending > 0 ? 'pending' : ($rejected > 0 ? 'rejected' : 'approved'),
        ];
    }

    private function approvalStatusKey($record): string
    {
        if ($record->director_approval_status === 'approved') {
            return 'approved';
        }

        if ($record->director_approval_status === 'rejected') {
            return 'rejected_director';
        }

        if ($record->admin_approval_status === 'rejected') {
            return 'rejected_admin';
        }

        if ($record->admin_approval_status === 'approved') {
            return 'pending_director';
        }

        return 'pending_admin';
    }

    private function displayName(string $type, $record): string
    {
        return match ($type) {
            'business_scale', 'delivery_method', 'payment_term', 'product', 'criteria', 'sub_criteria', 'distributor' => trim(($record->code ?? '-') . ' - ' . ($record->name ?? '-')),
            'alternative' => trim(($this->findDistributor($record->distributor_id)?->code ?? '-') . ' - ' . ($this->findDistributor($record->distributor_id)?->name ?? 'Alternatif')),
            'distributor_product' => trim(($this->findDistributor($record->distributor_id)?->code ?? '-') . ' <-> ' . ($this->findProduct($record->product_id)?->code ?? '-')),
            'alternative_value' => trim(($this->findAlternative($record->alternative_id)?->distributor_id ? ($this->findDistributor($this->findAlternative($record->alternative_id)?->distributor_id)?->code ?? '-') : '-') . ' - ' . ($this->findSubCriteria($record->sub_criteria_id)?->code ?? '-')),
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
            'alternative' => 'Distributor: ' . ($this->findDistributor($record->distributor_id)?->name ?? '-'),
            'distributor_product' => ($this->findDistributor($record->distributor_id)?->name ?? '-') . ' dengan ' . ($this->findProduct($record->product_id)?->name ?? '-'),
            'alternative_value' => ($this->findSubCriteria($record->sub_criteria_id)?->name ?? '-') . ' = ' . ($record->value ?? 0),
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

    private function findAlternative(?int $id): ?Alternative
    {
        return $id ? Alternative::query()->find($id) : null;
    }

    private function editUrl(string $type, $record, array $historyContext = []): ?string
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

        if ($historyContext === []) {
            return $baseUrl;
        }

        $query = array_filter([
            'return_to' => 'import-history',
            'history_page' => $historyContext['page'] ?? null,
            'history_search' => $historyContext['search'] ?? null,
            'history_batch' => $historyContext['batch'] ?? null,
            'history_item' => $historyContext['item'] ?? null,
            'history_item_page' => $historyContext['item_page'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');

        return $baseUrl . (str_contains($baseUrl, '?') ? '&' : '?') . http_build_query($query);
    }

    private function deleteUrl(string $type, $record): ?string
    {
        return match ($type) {
            'business_scale' => route('business_scale.destroy', $record->id),
            'delivery_method' => route('delivery_method.destroy', $record->id),
            'payment_term' => route('payment_term.destroy', $record->id),
            'distributor' => route('distributor.destroy', $record->id),
            'product' => route('product.destroy', $record->id),
            'criteria' => route('criteria.destroy', $record->id),
            'sub_criteria' => route('subcriteria.destroy', $record->id),
            'alternative' => route('alternative.destroy', $record->id),
            default => null,
        };
    }

    private function canDeleteFromHistory($record): bool
    {
        if (!$record->can_be_deleted_by_current_user) {
            return false;
        }

        if (auth()->user()?->role === 'staf' && !$this->isRejectedImportRecord($record)) {
            return false;
        }

        return $record->director_approval_status !== 'approved';
    }

    private function canEditFromHistory($record, $user): bool
    {
        if (!$record->can_be_edited_by_current_user) {
            return false;
        }

        if ($user->role === 'staf') {
            return $this->isRejectedImportRecord($record);
        }

        return true;
    }

    private function isRejectedImportRecord($record): bool
    {
        return $record->admin_approval_status === 'rejected'
            || $record->director_approval_status === 'rejected';
    }

    private function findRecord(string $type, int $id)
    {
        if (!isset(self::TYPES[$type])) {
            return null;
        }

        $modelClass = self::TYPES[$type]['model'];

        return $modelClass::query()->find($id);
    }
}
