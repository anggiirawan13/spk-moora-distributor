<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaymentTerm;
use App\Support\InputSanitizer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PaymentTermController extends Controller
{
    public function index()
    {
        $paymentTerms = PaymentTerm::all();
        return view('payment_term.index', compact('paymentTerms'));
    }

    public function create()
    {
        return view('payment_term.create');
    }

    public function show($id)
    {
        $paymentTerm = PaymentTerm::with(['createdBy', 'updatedBy'])->findOrFail($id);
        return view('payment_term.show', compact('paymentTerm'));
    }

    public function edit($id)
    {
        $paymentTerm = PaymentTerm::findOrFail($id);
        return view('payment_term.edit', compact('paymentTerm'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => InputSanitizer::clean($request->name) ?? '',
            'description' => InputSanitizer::clean($request->description),
        ]);

        $request->validate([
            'name' => 'required|unique:payment_terms,name',
            'description' => 'nullable|string'
        ]);

        try {
            PaymentTerm::create($request->only(['name', 'description']));

            return redirect()->route('payment_term.index')->with('success', 'Data termin pembayaran berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama termin pembayaran sudah digunakan, gunakan nama lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'name' => InputSanitizer::clean($request->name) ?? '',
            'description' => InputSanitizer::clean($request->description),
        ]);

        $this->validate($request, [
            'name' => 'required|unique:payment_terms,name,' . $id,
            'description' => 'nullable|string'
        ]);

        try {
            $paymentTerm = [
                'name' => $request->name,
                'description' => $request->description,
            ];

            PaymentTerm::whereId($id)->update($paymentTerm);

            return redirect()->route('payment_term.index')->with('success', 'Data termin pembayaran berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama termin pembayaran sudah digunakan, gunakan nama lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function destroy($id)
    {
        $paymentTerm = PaymentTerm::findOrFail($id);
        if ($paymentTerm->distributors()->count() > 0) {
            return redirect()->route('payment_term.index')
                ->with('error', 'Tidak dapat menghapus termin pembayaran karena masih digunakan oleh distributor');
        }

        $paymentTerm->delete();

        return redirect()->route('payment_term.index')->with('success', 'Data termin pembayaran berhasil dihapus');
    }
}
