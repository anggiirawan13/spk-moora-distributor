<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaymentTerm;
use App\Support\InputSanitizer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PaymentTermController extends Controller
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
        $paymentTerms = PaymentTerm::visibleTo(auth()->user())->get();
        return view('payment_term.index', compact('paymentTerms'));
    }

    public function create()
    {
        return view('payment_term.create');
    }

    public function show($id)
    {
        $paymentTerm = PaymentTerm::visibleTo(auth()->user())->with(['createdBy', 'updatedBy'])->findOrFail($id);
        return view('payment_term.show', compact('paymentTerm'));
    }

    public function edit($id)
    {
        $paymentTerm = PaymentTerm::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($paymentTerm->can_be_edited_by_current_user, 403);
        return view('payment_term.edit', compact('paymentTerm'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'code' => ($code = InputSanitizer::clean($request->code)) ? strtoupper($code) : '',
            'name' => InputSanitizer::clean($request->name) ?? '',
            'description' => InputSanitizer::clean($request->description),
        ]);

        $request->validate([
            'code' => 'required|string|max:20|unique:payment_terms,code',
            'name' => 'required',
            'description' => 'nullable|string'
        ]);

        try {
            PaymentTerm::create($request->only(['code', 'name', 'description']));

            return redirect()->route('payment_term.index')->with('success', 'Data termin pembayaran berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode termin pembayaran sudah digunakan, gunakan kode lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function update(Request $request, $id)
    {
        $paymentTermModel = PaymentTerm::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($paymentTermModel->can_be_edited_by_current_user, 403);

        $request->merge([
            'code' => ($code = InputSanitizer::clean($request->code)) ? strtoupper($code) : '',
            'name' => InputSanitizer::clean($request->name) ?? '',
            'description' => InputSanitizer::clean($request->description),
        ]);

        $this->validate($request, [
            'code' => 'required|string|max:20|unique:payment_terms,code,' . $id,
            'name' => 'required',
            'description' => 'nullable|string'
        ]);

        try {
            $paymentTerm = [
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
            ];

            $paymentTermModel->update($paymentTerm);
            $paymentTermModel->resetApprovalForRevision();

            if (request()->query('return_to') === 'import-history') {
                return redirect($this->historyRedirectUrl())->with('success', 'Data termin pembayaran berhasil diubah');
            }

            if (request()->query('return_to') === 'import-approval') {
                return redirect($this->approvalRedirectUrl())->with('success', 'Data termin pembayaran berhasil diubah');
            }

            return redirect()->route('payment_term.index')->with('success', 'Data termin pembayaran berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode termin pembayaran sudah digunakan, gunakan kode lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function destroy($id)
    {
        $paymentTerm = PaymentTerm::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($paymentTerm->can_be_deleted_by_current_user, 403);
        if ($paymentTerm->distributors()->count() > 0) {
            return redirect()->route('payment_term.index')
                ->with('error', 'Tidak dapat menghapus termin pembayaran karena masih digunakan oleh distributor');
        }

        $paymentTerm->delete();

        if (request()->query('return_to') === 'import-history') {
            return redirect($this->historyRedirectUrl())->with('success', 'Data termin pembayaran berhasil dihapus');
        }

        if (request()->query('return_to') === 'import-approval') {
            return redirect($this->approvalRedirectUrl())->with('success', 'Data termin pembayaran berhasil dihapus');
        }

        return redirect()->route('payment_term.index')->with('success', 'Data termin pembayaran berhasil dihapus');
    }
}
