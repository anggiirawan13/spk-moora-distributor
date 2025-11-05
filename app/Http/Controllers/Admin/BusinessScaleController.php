<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessScale;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BusinessScaleController extends Controller
{
    public function index()
    {
        $businessScales = BusinessScale::all();
        return view('admin.business_scale.index', compact('businessScales'));
    }

    public function create()
    {
        return view('admin.business_scale.create');
    }

    public function show($id)
    {
        $businessScale = BusinessScale::findOrFail($id);
        return view('admin.business_scale.show', compact('businessScale'));
    }

    public function edit($id)
    {
        $businessScale = BusinessScale::findOrFail($id);
        return view('admin.business_scale.edit', compact('businessScale'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:business_scales,name',
            'description' => 'nullable|string'
        ]);

        try {
            BusinessScale::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return redirect()->route('admin.business_scale.index')->with('success', 'Data skala bisnis berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama skala bisnis sudah digunakan, gunakan nama lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:business_scales,name,' . $id,
            'description' => 'nullable|string'
        ]);

        try {
            $businessScale = [
                'name' => $request->name,
                'description' => $request->description,
            ];

            BusinessScale::whereId($id)->update($businessScale);

            return redirect()->route('admin.business_scale.index')->with('success', 'Data skala bisnis berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama skala bisnis sudah digunakan, gunakan nama lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function destroy($id)
    {
        $businessScale = BusinessScale::findOrFail($id);
        
        // Check if business scale is used by any distributor
        if ($businessScale->distributors()->count() > 0) {
            return redirect()->route('admin.business_scale.index')
                ->with('error', 'Tidak dapat menghapus skala bisnis karena masih digunakan oleh distributor.');
        }
        
        $businessScale->delete();

        return redirect()->route('admin.business_scale.index')->with('success', 'Data skala bisnis berhasil dihapus');
    }
}