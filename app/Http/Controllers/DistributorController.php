<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Http\Controllers\Controller;
use App\Models\BusinessScale;
use App\Http\Requests\DistributorRequest;
use App\Models\Product;
use App\Models\PaymentTerm;
use App\Models\DeliveryMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DistributorController extends Controller
{
    private function historyRedirectUrl(): string
    {
        return route('import.excel.history', array_filter([
            'page' => request()->query('history_page'),
            'search' => request()->query('history_search'),
            'batch' => request()->query('history_batch'),
            'item' => request()->query('history_item'),
            'item_page' => request()->query('history_item_page'),
        ], fn ($value) => $value !== null && $value !== ''));
    }

    private function approvalRedirectUrl(): string
    {
        return route('import.approvals.index', array_filter([
            'batch' => request()->query('approval_batch'),
            'item' => request()->query('approval_item'),
            'item_page' => request()->query('approval_item_page'),
        ], fn ($value) => $value !== null && $value !== ''));
    }

    public function index(): View
    {
        $distributors = Distributor::visibleTo(auth()->user())->latest()->get();

        $distributors->transform(function ($distributor) {
            return [
                'id' => $distributor->id,
                'image_url' => asset('storage/distributor/' . ($distributor->image_name ?? 'default-image.jpg')),
                'code' => $distributor->code,
                'name' => $distributor->name,
                'npwp' => $distributor->npwp_formatted ?: 'Tidak diisi',
                'phone' => $distributor->phone,
                'email' => $distributor->email,
                'payment_term' => $distributor->paymentTerm?->name ?? 'N/A',
                'delivery_method' => $distributor->deliveryMethod?->name ?? 'N/A',
                'business_scale' => $distributor->businessScale?->name ?? 'N/A',
                'is_active' => $distributor->is_active,
                'approval_status_label' => $distributor->approval_status_label,
                'approval_reason' => $distributor->approval_reason,
                'can_edit' => $distributor->can_be_edited_by_current_user,
                'can_delete' => $distributor->can_be_deleted_by_current_user,
            ];
        });

        return view('distributor.index', compact('distributors'));
    }

    public function create(): View
    {
        $paymentTerms = PaymentTerm::visibleTo(auth()->user())->get();
        $deliveryMethods = DeliveryMethod::visibleTo(auth()->user())->get();
        $businessScales = BusinessScale::visibleTo(auth()->user())->get();

        return view('distributor.create', compact('paymentTerms', 'deliveryMethods', 'businessScales'));
    }

    public function store(DistributorRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $imageName = null;
            if ($request->hasFile('image_name')) {
                $image = $request->file('image_name')->store('distributor', 'public');
                $imageName = basename($image);
            }

            Distributor::create($request->except('image_name') + ['image_name' => $imageName]);
        }

        return redirect()->route('distributor.index')->with('success', 'Data distributor berhasil disimpan');
    }

    public function show($id)
    {
        $distributor = Distributor::visibleTo(auth()->user())->with(['paymentTerm', 'deliveryMethod', 'businessScale', 'createdBy', 'updatedBy'])->findOrFail($id);
        return view('distributor.show', compact('distributor'));
    }

    public function showComparisonForm(Request $request)
    {
        $products = Product::visibleTo(auth()->user())->get();

        $selectedProductId = $request->input('product_id');
        $distributors = collect();

        if ($selectedProductId) {
            $productSelected = Product::visibleTo(auth()->user())->findOrFail($selectedProductId);
            $distributors = $productSelected->distributors()->with([
                'products' => function ($query) use ($selectedProductId) {
                    $query->where('product_id', $selectedProductId);
                }
            ])->get();
        } else {
            $distributors = Distributor::visibleTo(auth()->user())->with('products')->get();
        }

        return view('distributor.compare_form', compact('products', 'distributors', 'selectedProductId'));
    }

    public function compare(Request $request)
    {
        if (!$request->distributor1) {
            return redirect()->route('distributor.compare.form')->with('error', 'Distributor pertama wajib dipilih');
        }

        if (!$request->distributor2) {
            return redirect()->route('distributor.compare.form')->with('error', 'Distributor kedua wajib dipilih');
        }

        $request->validate([
            'distributor1' => 'required|exists:distributors,id',
            'distributor2' => 'required|exists:distributors,id',
        ]);

        $distributor1 = Distributor::visibleTo(auth()->user())->with(['paymentTerm', 'deliveryMethod', 'businessScale'])->findOrFail($request->distributor1);
        $distributor2 = Distributor::visibleTo(auth()->user())->with(['paymentTerm', 'deliveryMethod', 'businessScale'])->findOrFail($request->distributor2);

        return view('distributor.compare', compact('distributor1', 'distributor2'));
    }

    public function edit($id)
    {
        $distributor = Distributor::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($distributor->can_be_edited_by_current_user, 403);
        $paymentTerms = PaymentTerm::manageableBy(auth()->user())->get();
        $deliveryMethods = DeliveryMethod::manageableBy(auth()->user())->get();
        $businessScales = BusinessScale::manageableBy(auth()->user())->get();

        return view('distributor.edit', compact('distributor', 'paymentTerms', 'deliveryMethods', 'businessScales'));
    }

    public function update(DistributorRequest $request, Distributor $distributor): RedirectResponse
    {
        $distributor->loadMissing('importBatch');
        abort_unless($distributor->can_be_edited_by_current_user, 403);

        if ($request->validated()) {
            $dataUpdate = $request->except('image_name');

            if ($request->hasFile('image_name')) {
                if ($distributor->image_name) {
                    Storage::delete('public/distributor/' . $distributor->image_name);
                }

                $image = $request->file('image_name')->store('distributor', 'public');
                $imageName = basename($image);

                $dataUpdate['image_name'] = $imageName;
            }

            $distributor->update($dataUpdate);
            $distributor->resetApprovalForRevision();
        }

        if (request()->query('return_to') === 'import-history') {
            return redirect($this->historyRedirectUrl())->with('success', 'Data distributor berhasil diubah');
        }

        if (request()->query('return_to') === 'import-approval') {
            return redirect($this->approvalRedirectUrl())->with('success', 'Data distributor berhasil diubah');
        }

        return redirect()->route('distributor.index')->with('success', 'Data distributor berhasil diubah');
    }

    public function destroy(Distributor $distributor): RedirectResponse
    {
        abort_unless($distributor->can_be_deleted_by_current_user, 403);

        if ($distributor->products()->exists()) {
            return redirect()->route('distributor.index')
                ->with('error', 'Distributor tidak dapat dihapus karena masih digunakan pada produk');
        }

        if ($distributor->alternative()->exists()) {
            return redirect()->route('distributor.index')
                ->with('error', 'Distributor tidak dapat dihapus karena masih digunakan pada alternatif');
        }

        if ($distributor->image_name) {
            Storage::delete('public/distributor/' . $distributor->image_name);
        }

        $distributor->delete();
        
        if (request()->query('return_to') === 'import-history') {
            return redirect($this->historyRedirectUrl())->with('success', 'Data distributor berhasil dihapus');
        }

        if (request()->query('return_to') === 'import-approval') {
            return redirect($this->approvalRedirectUrl())->with('success', 'Data distributor berhasil dihapus');
        }

        return redirect()->route('distributor.index')->with('success', 'Data distributor berhasil dihapus');
    }
}
