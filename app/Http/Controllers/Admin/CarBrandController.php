<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CarBrandController extends Controller
{
    public function index()
    {
        $carBrands = CarBrand::all();
        return view('admin.car_brand.index', compact('carBrands'));
    }

    public function create()
    {
        return view('admin.car_brand.create');
    }

    public function show($id)
    {
        $carBrand = CarBrand::findOrFail($id);
        return view('admin.car_brand.show', compact('carBrand'));
    }

    public function edit($id)
    {
        $carBrand = CarBrand::findOrFail($id);
        return view('admin.car_brand.edit', compact('carBrand'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:cars,name',
        ]);

        try {
            CarBrand::create([
                'name' => $request->name,
            ]);

            return redirect()->route('admin.car_brand.index')->with('success', 'Data berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode sudah digunakan, gunakan kode lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);


        try {
            $carBrand = [
                'name' => $request->name,
            ];

            CarBrand::whereId($id)->update($carBrand);

            return redirect()->route('admin.car_brand.index')->with('success', 'Data berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode sudah digunakan, gunakan kode lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function destroy($id)
    {
        $carBrand = CarBrand::findorfail($id);
        $carBrand->delete();

        return redirect()->route('admin.car_brand.index')->with('success', 'Data berhasil dihapus');
    }
}
