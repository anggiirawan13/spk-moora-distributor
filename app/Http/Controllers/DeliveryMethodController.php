<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMethod;
use App\Support\InputSanitizer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DeliveryMethodController extends Controller
{
    public function index()
    {
        $deliveryMethods = DeliveryMethod::visibleTo(auth()->user())->get();
        return view('delivery_method.index', compact('deliveryMethods'));
    }

    public function create()
    {
        return view('delivery_method.create');
    }

    public function show($id)
    {
        $deliveryMethod = DeliveryMethod::visibleTo(auth()->user())->with(['createdBy', 'updatedBy'])->findOrFail($id);
        return view('delivery_method.show', compact('deliveryMethod'));
    }

    public function edit($id)
    {
        $deliveryMethod = DeliveryMethod::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($deliveryMethod->can_be_edited_by_current_user, 403);
        return view('delivery_method.edit', compact('deliveryMethod'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'code' => ($code = InputSanitizer::clean($request->code)) ? strtoupper($code) : '',
            'name' => InputSanitizer::clean($request->name) ?? '',
            'description' => InputSanitizer::clean($request->description),
        ]);

        $request->validate([
            'code' => 'required|string|max:20|unique:delivery_methods,code',
            'name' => 'required',
            'description' => 'nullable|string'
        ]);

        try {
            DeliveryMethod::create($request->only(['code', 'name', 'description']));

            return redirect()->route('delivery_method.index')->with('success', 'Data metode pengiriman berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode metode pengiriman sudah digunakan, gunakan kode lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function update(Request $request, $id)
    {
        $deliveryMethodModel = DeliveryMethod::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($deliveryMethodModel->can_be_edited_by_current_user, 403);

        $request->merge([
            'code' => ($code = InputSanitizer::clean($request->code)) ? strtoupper($code) : '',
            'name' => InputSanitizer::clean($request->name) ?? '',
            'description' => InputSanitizer::clean($request->description),
        ]);

        $this->validate($request, [
            'code' => 'required|string|max:20|unique:delivery_methods,code,' . $id,
            'name' => 'required',
            'description' => 'nullable|string'
        ]);

        try {
            $deliveryMethod = [
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
            ];

            $deliveryMethodModel->update($deliveryMethod);

            return redirect()->route('delivery_method.index')->with('success', 'Data metode pengiriman berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Kode metode pengiriman sudah digunakan, gunakan kode lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function destroy($id)
    {
        $deliveryMethod = DeliveryMethod::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($deliveryMethod->can_be_deleted_by_current_user, 403);
        if ($deliveryMethod->distributors()->count() > 0) {
            return redirect()->route('delivery_method.index')
                ->with('error', 'Tidak dapat menghapus metode pengiriman karena masih digunakan oleh distributor');
        }

        $deliveryMethod->delete();

        return redirect()->route('delivery_method.index')->with('success', 'Data metode pengiriman berhasil dihapus');
    }
}
