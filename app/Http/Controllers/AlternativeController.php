<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\SubCriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlternativeController extends Controller
{
    public function index(): View
    {
        $criterias = Criteria::with('subCriteria')->orderBy('id')->get();

        $alternatives = Alternative::with(['values.subCriteria.criteria', 'distributor'])->get();

        $dataAlternatives = $alternatives->map(function ($alt) use ($criterias) {
            $data = [
                'id' => $alt->id,
                'name' => $alt->distributor?->name,
                'code' => $alt->distributor?->code
            ];

            foreach ($criterias as $criteria) {
                $value = $alt->values->firstWhere(function ($val) use ($criteria) {
                    return $val->subCriteria && $val->subCriteria->criteria_id === $criteria->id;
                });

                $data[$criteria->id] = $value && $value->subCriteria
                    ? $value->subCriteria->name
                    : '-';
            }

            return $data;
        });

        return view('alternative.index', [
            'criterias' => $criterias,
            'alternatives' => $dataAlternatives,
        ]);
    }

    public function create(): View
    {
        $distributors = Distributor::all();
        $criteria = Criteria::with('subCriteria')->orderBy('id', 'asc')->get();
        $distributorsData = $distributors->map(function ($distributor) {
            return [
                'id' => $distributor->id,
                'name' => $distributor->name,
                'code' => $distributor->code,
                'delivery_method' => $distributor->deliveryMethod->name ?? '-',
                'payment_term' => $distributor->paymentTerm->name ?? '-',
            ];
        });

        return view('alternative.create', compact('criteria', 'distributors', 'distributorsData'));
    }

    public function show($id)
    {
        $alternative = Alternative::with([
            'values.subCriteria.criteria',
            'distributor',
            'createdBy',
            'updatedBy',
        ])->findOrFail($id);

        return view('alternative.show', compact('alternative'));
    }

    public function edit($id): View
    {
        $distributors = Distributor::all();
        $alternative = Alternative::findOrFail($id);

        $criteria = Criteria::with('subCriteria')->orderBy('id', 'asc')->get();

        $distributorsData = $distributors->map(function ($distributor) {
            return [
                'id' => $distributor->id,
                'name' => $distributor->name,
                'code' => $distributor->code,
                'product' => $distributor->product->name ?? '-',
            ];
        });

        $selectedSubs = AlternativeValue::with('subCriteria')
            ->where('alternative_id', $id)
            ->get()
            ->mapWithKeys(function ($val) {
                return [$val->subCriteria->criteria_id => $val->sub_criteria_id];
            });

        return view('alternative.edit', compact('alternative', 'criteria', 'selectedSubs', 'distributors', 'distributorsData'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'distributor_id' => 'required|exists:distributors,id|unique:alternatives,distributor_id',
            'criteria' => 'required|array',
            'criteria.*' => 'required|numeric|exists:sub_criterias,id',
        ]);

        $alternative = Alternative::create([
            'distributor_id' => $request->distributor_id,
        ]);

        foreach ($request->criteria as $subCriteriaId) {
            $sub = SubCriteria::with('criteria')->find($subCriteriaId);

            AlternativeValue::create([
                'alternative_id' => $alternative->id,
                'sub_criteria_id' => $subCriteriaId,
                'value' => $sub->value ?? 0,
            ]);
        }

        return redirect()->route('alternative.index')->with('success', 'Data berhasil disimpan');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $alternative = Alternative::findOrFail($id);

        $request->validate([
            'distributor_id' => 'required|exists:distributors,id|unique:alternatives,distributor_id,' . $id,
            'criteria' => 'required|array',
            'criteria.*' => 'required|numeric|exists:sub_criterias,id',
        ]);

        $alternative->update(['distributor_id' => $request->distributor_id]);

        AlternativeValue::where('alternative_id', $id)->delete();

        foreach ($request->criteria as $subCriteriaId) {
            $sub = SubCriteria::with('criteria')->find($subCriteriaId);

            AlternativeValue::create([
                'alternative_id' => $alternative->id,
                'sub_criteria_id' => $subCriteriaId,
                'value' => $sub->value ?? 0,
            ]);
        }

        return redirect()->route('alternative.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id): RedirectResponse
    {
        $alternative = Alternative::findOrFail($id);

        AlternativeValue::where('alternative_id', $id)->delete();
        $alternative->delete();

        return redirect()->route('alternative.index')->with('success', 'Data berhasil dihapus');
    }
}
