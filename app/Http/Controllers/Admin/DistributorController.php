<?php

namespace App\Http\Controllers\Admin;

use App\Models\Distributor;
use App\Http\Controllers\Controller;
use App\Models\BusinessScale;
use App\Http\Requests\Admin\DistributorRequest;
use App\Models\Product;
use App\Models\PaymentTerm;
use App\Models\DeliveryMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DistributorController extends Controller
{
    public function index(): View
    {
        $distributors = Distributor::latest()->get();

        $distributors->transform(function ($distributor) {
            return [
                'id' => $distributor->id,
                'image' => '<a href="#" data-toggle="modal" data-target="#imageModal" onclick="showImage(\'' . $distributor->name . '\', \'' . asset('img/distributor/' . ($distributor->image_name ?? 'default-image.jpg')) . '\')">
                                <img class="default-img" src="' . asset('img/distributor/' . ($distributor->image_name ?? 'default-image.jpg')) . '" width="60">
                            </a>',
                'name' => $distributor->name,
                'company_name' => $distributor->company_name,
                'phone' => $distributor->phone,
                'email' => $distributor->email,
                'payment_term' => $distributor->paymentTerm?->name ?? 'N/A',
                'delivery_method' => $distributor->deliveryMethod?->name ?? 'N/A',
                'business_scale' => $distributor->businessScale?->name ?? 'N/A',
                'is_active' => $distributor->is_active,
            ];
        });

        return view('admin.distributor.index', compact('distributors'));
    }

    public function create(): View
    {
        $paymentTerms = PaymentTerm::all();
        $deliveryMethods = DeliveryMethod::all();
        $businessScales = BusinessScale::all();

        return view('admin.distributor.create', compact('paymentTerms', 'deliveryMethods', 'businessScales'));
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
        $distributor = Distributor::with(['paymentTerm', 'deliveryMethod', 'businessScale'])->findOrFail($id);
        return view('admin.distributor.show', compact('distributor'));
    }

    public function showComparisonForm()
    {
        $distributors = Distributor::all();

        return view('admin.distributor.compare_form', compact('distributors'));
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

        $distributor1 = Distributor::with(['paymentTerm', 'deliveryMethod', 'businessScale'])->findOrFail($request->distributor1);
        $distributor2 = Distributor::with(['paymentTerm', 'deliveryMethod', 'businessScale'])->findOrFail($request->distributor2);

        return view('admin.distributor.compare', compact('distributor1', 'distributor2'));
    }

    public function edit($id)
    {
        $distributor = Distributor::findOrFail($id);
        $paymentTerms = PaymentTerm::all();
        $deliveryMethods = DeliveryMethod::all();
        $businessScales = BusinessScale::all();

        return view('admin.distributor.edit', compact('distributor', 'paymentTerms', 'deliveryMethods', 'businessScales'));
    }

    public function update(DistributorRequest $request, Distributor $distributor): RedirectResponse
    {
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
        }

        return redirect()->route('distributor.index')->with('success', 'Data distributor berhasil diubah');
    }

    public function destroy(Distributor $distributor): RedirectResponse
    {
        if ($distributor->image_name) {
            Storage::delete('public/distributor/' . $distributor->image_name);
        }
        $distributor->delete();
        return redirect()->route('distributor.index')->with('success', 'Data distributor berhasil dihapus');
    }
}