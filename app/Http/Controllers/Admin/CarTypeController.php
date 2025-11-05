<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CarTypeController extends Controller
{
    public function index()
    {
        $carTypes = CarType::all();
        return view('admin.car_type.index', compact('carTypes'));
    }

    public function create()
    {
        return view('admin.car_type.create');
    }

    public function show($id)
    {
        $carType = CarType::findOrFail($id);
        return view('admin.car_type.show', compact('carType'));
    }

    public function edit($id)
    {
        $carType = CarType::findOrFail($id);
        return view('admin.car_type.edit', compact('carType'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:car_types,name',
        ]);

        try {
            CarType::create([
                'name' => $request->name,
            ]);

            return redirect()->route('admin.car_type.index')->with('success', 'Data berhasil disimpan');
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
            $carType = [
                'name' => $request->name,
            ];

            CarType::whereId($id)->update($carType);

            return redirect()->route('admin.car_type.index')->with('success', 'Data berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode sudah digunakan, gunakan kode lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function destroy($id)
    {
        $carType = CarType::findorfail($id);
        $carType->delete();

        return redirect()->route('admin.car_type.index')->with('success', 'Data berhasil dihapus');
    }
}
