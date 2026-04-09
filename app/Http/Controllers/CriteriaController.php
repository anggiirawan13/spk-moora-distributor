<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Criteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Database\QueryException;
use App\Support\InputSanitizer;

class CriteriaController extends Controller
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
        $criterias = Criteria::visibleTo(auth()->user())->orderby('code', 'asc')->get();

        $criterias->transform(function ($c) {
            return [
                'id' => $c->id,
                'code' => $c->code,
                'name' => $c->name,
                'weight' => $c->weight,
                'attribute_type' => ucwords(str_replace('_', ' ', $c->attribute_type)),
                'approval_status_label' => $c->approval_status_label,
                'approval_reason' => $c->approval_reason,
                'can_edit' => $c->can_be_edited_by_current_user,
                'can_delete' => $c->can_be_deleted_by_current_user,
            ];
        });

        return view('criteria.index', compact('criterias'));
    }

    public function create(): View
    {
        return view('criteria.create');
    }

    public function show($id)
    {
        $criteria = Criteria::visibleTo(auth()->user())->with(['createdBy', 'updatedBy'])->findOrFail($id);
        return view('criteria.show', compact('criteria'));
    }

    public function edit($id)
    {
        $criteria = Criteria::manageableBy(auth()->user())->findorfail($id);
        abort_unless($criteria->can_be_edited_by_current_user, 403);
        return view('criteria.edit', compact('criteria'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'code' => ($code = InputSanitizer::clean($request->code)) ? strtoupper($code) : '',
        ]);

        $request->validate([
            'code' => 'required|unique:criterias,code',
            'name' => 'required',
            'weight' => 'required|numeric',
            'attribute_type' => 'required'
        ]);

        try {
            Criteria::create([
                'code' => $request->code,
                'name' => $request->name,
                'weight' => $request->weight,
                'attribute_type' => $request->attribute_type,
            ]);

            return redirect()->route('criteria.index')->with('success', 'Data berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 10062) {
                return back()->withInput()->with('error', 'Kode sudah digunakan, gunakan kode lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $criteriaModel = Criteria::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($criteriaModel->can_be_edited_by_current_user, 403);

        $request->merge([
            'code' => ($code = InputSanitizer::clean($request->code)) ? strtoupper($code) : '',
        ]);

        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'weight' => 'required',
            'attribute_type' => 'required',
        ]);

        try {
            $criteriaData = [
                'code' => $request->code,
                'name' => $request->name,
                'weight' => $request->weight,
                'attribute_type' => $request->attribute_type,
            ];

            $criteriaModel->update($criteriaData);
            $criteriaModel->resetApprovalForRevision();

            if (request()->query('return_to') === 'import-history') {
                return redirect($this->historyRedirectUrl())->with('success', 'Data berhasil diubah');
            }

            if (request()->query('return_to') === 'import-approval') {
                return redirect($this->approvalRedirectUrl())->with('success', 'Data berhasil diubah');
            }

            return redirect()->route($criteriaModel->import_batch_id ? 'import.excel.history' : 'criteria.index')->with('success', 'Data berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 10062) {
                return back()->withInput()->with('error', 'Kode sudah digunakan, gunakan kode lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function destroy($id): RedirectResponse
    {
        $criteria = Criteria::manageableBy(auth()->user())->findorfail($id);
        abort_unless($criteria->can_be_deleted_by_current_user, 403);
        $criteria->delete();

        return redirect()->route('criteria.index')->with('success', 'Data berhasil dihapus');
    }
}
