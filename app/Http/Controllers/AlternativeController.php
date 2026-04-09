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
        $criterias = Criteria::visibleTo(auth()->user())->with('subCriteria')->orderBy('id')->get();

        $alternatives = Alternative::visibleTo(auth()->user())->with(['values.subCriteria.criteria', 'distributor'])->get();

        $dataAlternatives = $alternatives->map(function ($alt) use ($criterias) {
            $data = [
                'id' => $alt->id,
                'name' => $alt->distributor?->name,
                'code' => $alt->distributor?->code,
                'approval_status_label' => $alt->approval_status_label,
                'approval_reason' => $alt->approval_reason,
                'can_edit' => $alt->can_be_edited_by_current_user,
                'can_delete' => $alt->can_be_deleted_by_current_user,
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
        $distributors = Distributor::visibleTo(auth()->user())->get();
        $criteria = Criteria::visibleTo(auth()->user())->with('subCriteria')->orderBy('id', 'asc')->get();
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
        $alternative = Alternative::visibleTo(auth()->user())->with([
            'values.subCriteria.criteria',
            'distributor',
            'createdBy',
            'updatedBy',
        ])->findOrFail($id);

        return view('alternative.show', compact('alternative'));
    }

    public function edit($id): View
    {
        $user = auth()->user();
        $distributors = Distributor::manageableBy($user)->get();
        $alternative = Alternative::manageableBy($user)->findOrFail($id);
        abort_unless($alternative->can_be_edited_by_current_user, 403);

        $criteria = Criteria::manageableBy($user)->orderBy('id', 'asc')->get();
        $criteria->each(function ($criterion) use ($user) {
            $criterion->setRelation(
                'subCriteria',
                SubCriteria::manageableBy($user)->where('criteria_id', $criterion->id)->orderBy('value')->get()
            );
        });

        $selectedDistributor = Distributor::manageableBy($user)->find($alternative->distributor_id)
            ?? Distributor::query()->find($alternative->distributor_id);
        if ($selectedDistributor) {
            $alternative->setRelation('distributor', $selectedDistributor);
        }

        $distributorsData = $distributors->map(function ($distributor) {
            return [
                'id' => $distributor->id,
                'name' => $distributor->name,
                'code' => $distributor->code,
                'product' => $distributor->product->name ?? '-',
            ];
        });

        $selectedSubs = AlternativeValue::query()
            ->where('alternative_id', $id)
            ->get()
            ->mapWithKeys(function ($val) {
                $subCriteria = SubCriteria::query()->find($val->sub_criteria_id);

                return $subCriteria ? [$subCriteria->criteria_id => $val->sub_criteria_id] : [];
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

            $payload = [
                'alternative_id' => $alternative->id,
                'sub_criteria_id' => $subCriteriaId,
                'value' => $sub->value ?? 0,
            ];

            if ($alternative->import_batch_id) {
                $payload['import_batch_id'] = $alternative->import_batch_id;
            }

            AlternativeValue::create($payload);
        }

        return redirect()->route('alternative.index')->with('success', 'Data berhasil disimpan');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $alternative = Alternative::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($alternative->can_be_edited_by_current_user, 403);

        $request->validate([
            'distributor_id' => 'required|exists:distributors,id|unique:alternatives,distributor_id,' . $id,
            'criteria' => 'required|array',
            'criteria.*' => 'required|numeric|exists:sub_criterias,id',
        ]);

        $alternative->update(['distributor_id' => $request->distributor_id]);

        AlternativeValue::where('alternative_id', $id)->delete();

        foreach ($request->criteria as $subCriteriaId) {
            $sub = SubCriteria::with('criteria')->find($subCriteriaId);

            $payload = [
                'alternative_id' => $alternative->id,
                'sub_criteria_id' => $subCriteriaId,
                'value' => $sub->value ?? 0,
            ];

            if ($alternative->import_batch_id) {
                $payload['import_batch_id'] = $alternative->import_batch_id;
            }

            AlternativeValue::create($payload);
        }

        $alternative->resetApprovalForRevision();

        if (request()->query('return_to') === 'import-history') {
            return redirect($this->historyRedirectUrl())->with('success', 'Data berhasil diubah');
        }

        if (request()->query('return_to') === 'import-approval') {
            return redirect($this->approvalRedirectUrl())->with('success', 'Data berhasil diubah');
        }

        return redirect()->route($alternative->import_batch_id ? 'import.excel.history' : 'alternative.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id): RedirectResponse
    {
        $alternative = Alternative::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($alternative->can_be_deleted_by_current_user, 403);

        AlternativeValue::where('alternative_id', $id)->delete();
        $alternative->delete();

        if (request()->query('return_to') === 'import-history') {
            return redirect($this->historyRedirectUrl())->with('success', 'Data berhasil dihapus');
        }

        if (request()->query('return_to') === 'import-approval') {
            return redirect($this->approvalRedirectUrl())->with('success', 'Data berhasil dihapus');
        }

        return redirect()->route($alternative->import_batch_id ? 'import.excel.history' : 'alternative.index')->with('success', 'Data berhasil dihapus');
    }
}
