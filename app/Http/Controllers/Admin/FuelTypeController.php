<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FuelType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class FuelTypeController extends Controller
{
    public function index()
    {
        $fuelTypes = FuelType::all();
        return view('admin.fuel_type.index', compact('fuelTypes'));
    }

    public function create()
    {
        return view('admin.fuel_type.create');
    }

    public function show($id)
    {
        $fuelType = FuelType::findOrFail($id);
        return view('admin.fuel_type.show', compact('fuelType'));
    }

    public function edit($id)
    {
        $fuelType = FuelType::findOrFail($id);
        return view('admin.fuel_type.edit', compact('fuelType'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:fuel_types,name',
        ]);

        try {
            FuelType::create([
                'name' => $request->name,
            ]);

            return redirect()->route('admin.fuel_type.index')->with('success', 'Data berhasil disimpan');
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
            $fuelType = [
                'name' => $request->name,
            ];

            FuelType::whereId($id)->update($fuelType);

            return redirect()->route('admin.fuel_type.index')->with('success', 'Data berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode sudah digunakan, gunakan kode lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function destroy($id)
    {
        $fuelType = FuelType::findorfail($id);
        $fuelType->delete();

        return redirect()->route('admin.fuel_type.index')->with('success', 'Data berhasil dihapus');
    }
}
