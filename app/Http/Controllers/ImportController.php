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
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

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

        $batchQuery = ImportBatch::with('importedBy')->latest();

        if ((int) $user->is_admin !== 1) {
            $batchQuery->where('imported_by', $user->id);
        }

        if ($search !== '') {
            $batchQuery->where(function ($query) use ($search, $user) {
                $query->where('original_file_name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');

                if ((int) $user->is_admin === 1) {
                    $query->orWhereHas('importedBy', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
                }
            });
        }

        $importBatches = $batchQuery->paginate(10)->withQueryString();

        $importBatches->setCollection(
            $importBatches->getCollection()
                ->map(function (ImportBatch $batch) use ($user) {
                    $items = $this->batchItems($batch, $user);

                    return [
                        'batch' => $batch,
                        'items' => $items,
                        'summary' => $this->batchSummary($items),
                    ];
                })
                ->filter(fn ($entry) => $entry['items']->isNotEmpty())
                ->values()
        );

        return view('import.history', compact('importBatches', 'search'));
    }

    public function preview(Request $request): View
    {
        abort(404);
    }

    public function store(Request $request)
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
        ]);
        $context->importBatchId = $importBatch->id;
        $import = new MasterImport($errors, $stats, $context, false);

        ImportBatchState::activate($importBatch->id);

        try {
            Excel::import($import, $file);
        } finally {
            ImportBatchState::clear();
        }

        $statsSummary = $stats->all();
        $importBatch->update([
            'stats' => $statsSummary,
        ]);
        $successMessage = auth()->user()->is_admin == 1
            ? 'Import berhasil. Batch otomatis disetujui admin dan menunggu persetujuan Direktur Utama.'
            : 'Import berhasil dan menunggu persetujuan admin.';

        if ($errors->has()) {
            $errorList = $errors->all();
            $message = $errorList[0] ?? 'Terjadi error saat import.';

            if (count($errorList) > 1) {
                $message .= ' Dan ' . (count($errorList) - 1) . ' error lainnya.';
            }

            return redirect()
                ->route('import.excel.index')
                ->with('error', $message);
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
                    'approval_reason' => $record->approval_reason,
                    'edit_url' => $record->can_be_edited_by_current_user ? $this->editUrl($type, $record) : null,
                    'delete_url' => $record->can_be_deleted_by_current_user ? $this->deleteUrl($type, $record) : null,
                    'can_edit' => $record->can_be_edited_by_current_user,
                    'can_delete' => $record->can_be_deleted_by_current_user,
                ]);
            }
        }

        return $items->sortBy(['type_label', 'display_name'])->values();
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
        return [
            'total' => $items->count(),
            'pending' => $items->where('approval_status_label', 'Menunggu Admin')->count()
                + $items->where('approval_status_label', 'Menunggu Direktur Utama')->count(),
            'approved' => $items->where('approval_status_label', 'Disetujui Direktur Utama')->count(),
            'rejected' => $items->filter(fn ($item) => str_contains($item['approval_status_label'], 'Ditolak'))->count(),
        ];
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
}
