<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Distributor;
use App\Support\InputSanitizer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::visibleTo(auth()->user())->with('distributors')->get();
        return view('product.index', compact('products'));
    }

    public function create()
    {
        $distributors = Distributor::visibleTo(auth()->user())->get();
        return view('product.create', compact('distributors'));
    }

    public function show($id)
    {
        $product = Product::visibleTo(auth()->user())->with(['distributors', 'createdBy', 'updatedBy'])->findOrFail($id);
        return view('product.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::manageableBy(auth()->user())->with('distributors')->findOrFail($id);
        abort_unless($product->can_be_edited_by_current_user, 403);
        $distributors = Distributor::manageableBy(auth()->user())->get();
        $selectedDistributors = $product->distributors->pluck('id')->toArray();

        return view('product.edit', compact('product', 'distributors', 'selectedDistributors'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'code' => ($code = InputSanitizer::clean($request->code)) ? strtoupper($code) : '',
            'name' => InputSanitizer::clean($request->name) ?? '',
            'description' => InputSanitizer::clean($request->description),
        ]);

        $request->validate([
            'code' => 'required|string|max:20|unique:products,code',
            'name' => 'required|unique:products,name',
            'description' => 'nullable|string',
            'distributors' => 'array',
            'distributors.*' => 'exists:distributors,id'
        ]);

        try {
            $product = Product::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->has('distributors')) {
                $product->distributors()->attach($request->distributors);
            }

            return redirect()->route('product.index')->with('success', 'Data produk berhasil disimpan');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama produk sudah digunakan, gunakan nama lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function update(Request $request, $id)
    {
        $productModel = Product::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($productModel->can_be_edited_by_current_user, 403);

        $request->merge([
            'code' => ($code = InputSanitizer::clean($request->code)) ? strtoupper($code) : '',
            'name' => InputSanitizer::clean($request->name) ?? '',
            'description' => InputSanitizer::clean($request->description),
        ]);

        $this->validate($request, [
            'code' => 'required|string|max:20|unique:products,code,' . $id,
            'name' => 'required|unique:products,name,' . $id,
            'description' => 'nullable|string',
            'distributors' => 'array',
            'distributors.*' => 'exists:distributors,id'
        ]);

        try {
            $product = $productModel;

            $product->update([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($product->import_batch_id) {
                $pivotData = collect($request->distributors ?? [])->mapWithKeys(function ($distributorId) use ($product) {
                    return [
                        $distributorId => [
                            'import_batch_id' => $product->import_batch_id,
                            'admin_approval_status' => auth()->user()->is_admin == 1 ? 'approved' : 'pending',
                            'admin_approval_note' => null,
                            'admin_approved_at' => auth()->user()->is_admin == 1 ? now() : null,
                            'admin_approved_by' => auth()->user()->is_admin == 1 ? auth()->id() : null,
                            'director_approval_status' => 'pending',
                            'director_approval_note' => null,
                            'director_approved_at' => null,
                            'director_approved_by' => null,
                            'updated_by' => auth()->id(),
                            'created_by' => auth()->id(),
                        ],
                    ];
                })->toArray();

                $product->distributors()->sync($pivotData);
            } else {
                $product->distributors()->sync($request->distributors ?? []);
            }

            return redirect()->route('product.index')->with('success', 'Data produk berhasil diubah');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Nama produk sudah digunakan, gunakan nama lain');
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi');
        }
    }

    public function destroy($id)
    {
        $product = Product::manageableBy(auth()->user())->findOrFail($id);
        abort_unless($product->can_be_deleted_by_current_user, 403);

        $product->distributors()->detach();

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Data produk berhasil dihapus');
    }
}
