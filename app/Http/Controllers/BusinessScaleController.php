<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessScale;
use App\Support\InputSanitizer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BusinessScaleController extends Controller
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

    public function index()
    {
        $businessScales = BusinessScale::visibleTo(auth()->user())->get();
        return view('business_scale.index', compact('businessScales'));
    }

    public function create()
    {
        return view('business_scale.create');
    }

    public function show($id)
    {
        $businessScale = BusinessScale::visibleTo(auth()->user())->with(['createdBy', 'updatedBy'])->findOrFail($id);
        return view('business_scale.show', compact('businessScale'));
    }

    public function edit($id)
    {
        $businessScale = BusinessScale::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($businessScale->can_be_edited_by_current_user, 403);
        return view('business_scale.edit', compact('businessScale'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'code' => ($code = InputSanitizer::clean($request->code)) ? strtoupper($code) : '',
            'name' => InputSanitizer::clean($request->name) ?? '',
            'description' => InputSanitizer::clean($request->description),
        ]);

        $request->validate([
            'code' => 'required|string|max:20|unique:business_scales,code',
            'name' => 'required',
            'description' => 'nullable|string'
        ]);

        try {
            BusinessScale::create($request->only(['code', 'name', 'description']));

            return redirect()->route('business_scale.index')->with('success', 'Data skala bisnis berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode skala bisnis sudah digunakan, gunakan kode lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function update(Request $request, $id)
    {
        $businessScaleModel = BusinessScale::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($businessScaleModel->can_be_edited_by_current_user, 403);

        $request->merge([
            'code' => ($code = InputSanitizer::clean($request->code)) ? strtoupper($code) : '',
            'name' => InputSanitizer::clean($request->name) ?? '',
            'description' => InputSanitizer::clean($request->description),
        ]);

        $this->validate($request, [
            'code' => 'required|string|max:20|unique:business_scales,code,' . $id,
            'name' => 'required',
            'description' => 'nullable|string'
        ]);

        try {
            $businessScale = [
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
            ];

            $businessScaleModel->update($businessScale);
            $businessScaleModel->resetApprovalForRevision();

            if (request()->query('return_to') === 'import-history') {
                return redirect($this->historyRedirectUrl())->with('success', 'Data skala bisnis berhasil diubah');
            }

            if (request()->query('return_to') === 'import-approval') {
                return redirect($this->approvalRedirectUrl())->with('success', 'Data skala bisnis berhasil diubah');
            }

            return redirect()->route('business_scale.index')->with('success', 'Data skala bisnis berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode skala bisnis sudah digunakan, gunakan kode lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function destroy($id)
    {
        $businessScale = BusinessScale::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($businessScale->can_be_deleted_by_current_user, 403);

        if ($businessScale->distributors()->count() > 0) {
            return redirect()->route('business_scale.index')
                ->with('error', 'Tidak dapat menghapus skala bisnis karena masih digunakan oleh distributor');
        }

        $businessScale->delete();

        if (request()->query('return_to') === 'import-history') {
            return redirect($this->historyRedirectUrl())->with('success', 'Data skala bisnis berhasil dihapus');
        }

        if (request()->query('return_to') === 'import-approval') {
            return redirect($this->approvalRedirectUrl())->with('success', 'Data skala bisnis berhasil dihapus');
        }

        return redirect()->route('business_scale.index')->with('success', 'Data skala bisnis berhasil dihapus');
    }
}
