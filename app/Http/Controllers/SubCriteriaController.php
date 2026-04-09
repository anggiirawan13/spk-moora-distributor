<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SubCriteria;
use App\Models\Criteria;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubCriteriaController extends Controller
{
    public function index()
    {
        $criteria = Criteria::visibleTo(auth()->user())->with('subCriteria')->orderBy('code')->get();
        return view('sub_criteria.index', compact('criteria'));
    }

    public function create(Request $request)
    {
        $criteria = Criteria::manageableBy(auth()->user())->findOrFail($request->criteria_id);
        return view('sub_criteria.create', compact('criteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'criteria_id' => 'required|exists:criterias,id',
            'name' => 'required|string|max:255',
            'value' => 'required|integer|min:1',
        ]);

        SubCriteria::create($request->all());

        return redirect()->route('subcriteria.index')->with('success', 'Sub Kriteria berhasil ditambahkan');
    }

    public function show($id)
    {
        $subCriteria = SubCriteria::visibleTo(auth()->user())->with(['createdBy', 'updatedBy'])->findOrFail($id);
        return view('sub_criteria.show', compact('subCriteria'));
    }

    public function edit($id)
    {
        $subCriteria = SubCriteria::manageableBy(auth()->user())->findorfail($id);
        abort_unless($subCriteria->can_be_edited_by_current_user, 403);
        $criteria = Criteria::manageableBy(auth()->user())->find($subCriteria->criteria_id) ?? Criteria::query()->findOrFail($subCriteria->criteria_id);
        $criteria->setRelation(
            'subCriteria',
            SubCriteria::manageableBy(auth()->user())->where('criteria_id', $criteria->id)->orderBy('value')->get()
        );
        $subCriteria->setRelation('criteria', $criteria);
        return view('sub_criteria.edit', compact('subCriteria'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $subCriteriaModel = SubCriteria::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($subCriteriaModel->can_be_edited_by_current_user, 403);

        $this->validate($request, [
            'criteria_id' => 'required|exists:criterias,id',
            'name' => 'required|string|max:255',
            'value' => 'required|integer|min:1',
        ]);

        try {
            $subCriteria = [
                'name' => $request->name,
                'value' => $request->value
            ];

            $subCriteriaModel->update($subCriteria);

            return redirect()
                ->route($subCriteriaModel->import_batch_id ? 'import.excel.history' : 'subcriteria.index')
                ->with('success', 'Data berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 10062) {
                return back()->withInput()->with('error', 'Kode sudah digunakan, gunakan kode lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function destroy($id)
    {
        $criteria = SubCriteria::manageableBy(auth()->user())->findorfail($id);
        abort_unless($criteria->can_be_deleted_by_current_user, 403);
        $criteria->delete();

        return redirect()
            ->route($criteria->import_batch_id ? 'import.excel.history' : 'subcriteria.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
