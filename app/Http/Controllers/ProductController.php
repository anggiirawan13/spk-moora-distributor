<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Distributor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('distributors')->get();
        return view('product.index', compact('products'));
    }

    public function create()
    {
        $distributors = Distributor::all();
        return view('product.create', compact('distributors'));
    }

    public function show($id)
    {
        $product = Product::with('distributors')->findOrFail($id);
        return view('product.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('distributors')->findOrFail($id);
        $distributors = Distributor::all();
        $selectedDistributors = $product->distributors->pluck('id')->toArray();

        return view('product.edit', compact('product', 'distributors', 'selectedDistributors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products,name',
            'description' => 'nullable|string',
            'distributors' => 'array',
            'distributors.*' => 'exists:distributors,id'
        ]);

        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Attach selected distributors
            if ($request->has('distributors')) {
                $product->distributors()->attach($request->distributors);
            }

            return redirect()->route('product.index')->with('success', 'Data produk berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama produk sudah digunakan, gunakan nama lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:products,name,' . $id,
            'description' => 'nullable|string',
            'distributors' => 'array',
            'distributors.*' => 'exists:distributors,id'
        ]);

        try {
            $product = Product::findOrFail($id);

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Sync distributors (add/remove as needed)
            $product->distributors()->sync($request->distributors ?? []);

            return redirect()->route('product.index')->with('success', 'Data produk berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama produk sudah digunakan, gunakan nama lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Detach all distributors before deleting
        $product->distributors()->detach();

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Data produk berhasil dihapus');
    }
}