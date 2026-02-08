<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMethod;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DeliveryMethodController extends Controller
{
    public function index()
    {
        $deliveryMethods = DeliveryMethod::all();
        return view('delivery_method.index', compact('deliveryMethods'));
    }

    public function create()
    {
        return view('delivery_method.create');
    }

    public function show($id)
    {
        $deliveryMethod = DeliveryMethod::findOrFail($id);
        return view('delivery_method.show', compact('deliveryMethod'));
    }

    public function edit($id)
    {
        $deliveryMethod = DeliveryMethod::findOrFail($id);
        return view('delivery_method.edit', compact('deliveryMethod'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:delivery_methods,name',
            'description' => 'nullable|string'
        ]);

        try {
            DeliveryMethod::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return redirect()->route('delivery_method.index')->with('success', 'Data metode pengiriman berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama metode pengiriman sudah digunakan, gunakan nama lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:delivery_methods,name,' . $id,
            'description' => 'nullable|string'
        ]);

        try {
            $deliveryMethod = [
                'name' => $request->name,
                'description' => $request->description,
            ];

            DeliveryMethod::whereId($id)->update($deliveryMethod);

            return redirect()->route('delivery_method.index')->with('success', 'Data metode pengiriman berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama metode pengiriman sudah digunakan, gunakan nama lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function destroy($id)
    {
        $deliveryMethod = DeliveryMethod::findOrFail($id);
        if ($deliveryMethod->distributors()->count() > 0) {
            return redirect()->route('delivery_method.index')
                ->with('error', 'Tidak dapat menghapus metode pengiriman karena masih digunakan oleh distributor');
        }

        $deliveryMethod->delete();

        return redirect()->route('delivery_method.index')->with('success', 'Data metode pengiriman berhasil dihapus');
    }
}