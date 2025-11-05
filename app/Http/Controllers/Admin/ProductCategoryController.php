<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $productCategories = ProductCategory::all();
        return view('admin.product_category.index', compact('productCategories'));
    }

    public function create()
    {
        return view('admin.product_category.create');
    }

    public function show($id)
    {
        $productCategory = ProductCategory::findOrFail($id);
        return view('admin.product_category.show', compact('productCategory'));
    }

    public function edit($id)
    {
        $productCategory = ProductCategory::findOrFail($id);
        return view('admin.product_category.edit', compact('productCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:product_categories,name',
            'description' => 'nullable|string'
        ]);

        try {
            ProductCategory::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return redirect()->route('admin.product_category.index')->with('success', 'Data kategori produk berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama kategori produk sudah digunakan, gunakan nama lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:product_categories,name,' . $id,
            'description' => 'nullable|string'
        ]);

        try {
            $productCategory = [
                'name' => $request->name,
                'description' => $request->description,
            ];

            ProductCategory::whereId($id)->update($productCategory);

            return redirect()->route('admin.product_category.index')->with('success', 'Data kategori produk berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama kategori produk sudah digunakan, gunakan nama lain.');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function destroy($id)
    {
        $productCategory = ProductCategory::findOrFail($id);
        
        // Check if product category is used by any distributor
        if ($productCategory->distributors()->count() > 0) {
            return redirect()->route('admin.product_category.index')
                ->with('error', 'Tidak dapat menghapus kategori produk karena masih digunakan oleh distributor.');
        }
        
        $productCategory->delete();

        return redirect()->route('admin.product_category.index')->with('success', 'Data kategori produk berhasil dihapus');
    }
}