<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessScale;
use App\Support\InputSanitizer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BusinessScaleController extends Controller
{
    public function index()
    {
        $businessScales = BusinessScale::all();
        return view('business_scale.index', compact('businessScales'));
    }

    public function create()
    {
        return view('business_scale.create');
    }

    public function show($id)
    {
        $businessScale = BusinessScale::with(['createdBy', 'updatedBy'])->findOrFail($id);
        return view('business_scale.show', compact('businessScale'));
    }

    public function edit($id)
    {
        $businessScale = BusinessScale::findOrFail($id);
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

            BusinessScale::whereId($id)->update($businessScale);

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
        $businessScale = BusinessScale::findOrFail($id);

        if ($businessScale->distributors()->count() > 0) {
            return redirect()->route('business_scale.index')
                ->with('error', 'Tidak dapat menghapus skala bisnis karena masih digunakan oleh distributor');
        }

        $businessScale->delete();

        return redirect()->route('business_scale.index')->with('success', 'Data skala bisnis berhasil dihapus');
    }
}
