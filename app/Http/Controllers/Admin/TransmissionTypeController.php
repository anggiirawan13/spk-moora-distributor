<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransmissionType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TransmissionTypeController extends Controller
{
    public function index()
    {
        $transmissionTypes = TransmissionType::all();
        return view('admin.transmission_type.index', compact('transmissionTypes'));
    }

    public function create()
    {
        return view('admin.transmission_type.create');
    }

    public function show($id)
    {
        $transmissionType = TransmissionType::findOrFail($id);
        return view('admin.transmission_type.show', compact('transmissionType'));
    }

    public function edit($id)
    {
        $transmissionType = TransmissionType::findOrFail($id);
        return view('admin.transmission_type.edit', compact('transmissionType'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:transmission_types,name',
        ]);

        try {
            TransmissionType::create([
                'name' => $request->name,
            ]);

            return redirect()->route('admin.transmission_type.index')->with('success', 'Data berhasil disimpan');
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
            $transmissionType = [
                'name' => $request->name,
            ];

            TransmissionType::whereId($id)->update($transmissionType);

            return redirect()->route('admin.transmission_type.index')->with('success', 'Data berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode sudah digunakan, gunakan kode lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function destroy($id)
    {
        $transmissionType = TransmissionType::findorfail($id);
        $transmissionType->delete();

        return redirect()->route('admin.transmission_type.index')->with('success', 'Data berhasil dihapus');
    }
}
