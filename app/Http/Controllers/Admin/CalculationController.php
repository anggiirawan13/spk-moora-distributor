<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Criteria;
use App\Models\Alternative;
use App\Models\Product;

class CalculationController extends Controller
{
    public function calculation(Request $request)
    {
        // Ambil semua produk untuk dropdown
        $products = Product::withCount('distributors')->get();

        $alternatives = null;
        $productSelected = null;

        // Jika ada product_id yang dipilih
        if ($request->has('product_id') && $request->product_id) {
            $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);

            $productId = $request->product_id;
            $productSelected = Product::findOrFail($productId);

            // Hanya ambil distributor yang menyediakan produk yang dipilih
            $distributors = $productSelected->distributors;
            $alternatives = Alternative::whereIn('distributor_id', $distributors->pluck('id'))->get();

            if ($alternatives->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data alternatif untuk distributor yang menyediakan produk ini.')->with('products', $products);
            }
        } else {
            // Jika tidak ada product_id yang dipilih, hitung semua distributor
            $alternatives = Alternative::all();

            if ($alternatives->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data alternatif yang tersedia.')->with('products', $products);
            }
        }

        $criteria = Criteria::with(['subCriteria'])->get();

        // Load relationships untuk alternatives yang sudah difilter
        $alternatives->load(['values.subCriteria', 'distributor']);

        // Normalisasi bobot kriteria
        $totalWeight = $criteria->sum('weight') ?: 1;
        $weight = $criteria->pluck('weight', 'id')->map(fn($w) => $w / $totalWeight);

        // Ambil semua nilai alternatif berdasarkan sub_criterias.value
        $altValues = [];

        foreach ($alternatives as $alt) {
            foreach ($criteria as $c) {
                $altValues[$alt->id][$c->id] = 0;
                foreach ($alt->values as $val) {
                    $sub = $val->subCriteria;
                    if ($sub && $sub->criteria_id === $c->id) {
                        $altValues[$alt->id][$c->id] = $sub->value;
                        break;
                    }
                }
            }
        }

        // Normalisasi nilai alternatif per kriteria (sqrt(sum^2))
        $normDivisor = [];
        foreach ($criteria as $c) {
            $sumSquares = 0;
            foreach ($alternatives as $alt) {
                $val = $altValues[$alt->id][$c->id] ?? 0;
                $sumSquares += pow($val, 2);
            }
            $normDivisor[$c->id] = sqrt($sumSquares) ?: 1;
        }

        // Normalisasi dan perhitungan MOORA
        $normalization = [];
        $valueMoora = [];

        foreach ($alternatives as $alt) {
            $benefit = 0;
            $cost = 0;

            foreach ($criteria as $c) {
                $raw = $altValues[$alt->id][$c->id] ?? 0;
                $norm = $raw / $normDivisor[$c->id];
                $weighted = $norm * $weight[$c->id];

                $normalization[$alt->id][$c->id] = $weighted;

                if (strtolower($c->attribute_type) === 'benefit') {
                    $benefit += $weighted;
                } else {
                    $cost += $weighted;
                }
            }

            $valueMoora[$alt->id] = $benefit - $cost;
        }

        // Urutkan berdasarkan nilai MOORA tertinggi ke terendah
        arsort($valueMoora);

        return view('admin.moora.calculation', compact(
            'alternatives',
            'criteria',
            'normalization',
            'weight',
            'valueMoora',
            'normDivisor',
            'productSelected',
            'altValues',
            'products'
        ));

        // Jika pertama kali buka, hanya tampilkan form
        return view('admin.moora.calculation', compact('products'));
    }

    public function downloadPDF(Request $request)
    {
        // Ambil semua produk untuk dropdown
        $products = Product::withCount('distributors')->get();

        $alternatives = null;
        $productSelected = null;

        // Jika ada product_id yang dipilih
        if ($request->product_id) {
            $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);

            $productId = $request->product_id;
            $productSelected = Product::findOrFail($productId);

            // Hanya ambil distributor yang menyediakan produk yang dipilih
            $distributors = $productSelected->distributors;
            $alternatives = Alternative::whereIn('distributor_id', $distributors->pluck('id'))->get();

            if ($alternatives->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data alternatif untuk distributor yang menyediakan produk ini.')->with('products', $products);
            }
        } else {
            // Jika tidak ada product_id yang dipilih, hitung semua distributor
            $alternatives = Alternative::all();
            
            if ($alternatives->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data alternatif yang tersedia.')->with('products', $products);
            }
        }

        $criteria = Criteria::with(['subCriteria'])->get();
        
        // Load relationships untuk alternatives yang sudah difilter
        $alternatives->load(['values.subCriteria', 'distributor']);

        // Normalisasi bobot kriteria
        $totalWeight = $criteria->sum('weight') ?: 1;
        $weight = $criteria->pluck('weight', 'id')->map(fn($w) => $w / $totalWeight);

        // Ambil semua nilai alternatif berdasarkan sub_criterias.value
        $altValues = [];

        foreach ($alternatives as $alt) {
            foreach ($criteria as $c) {
                $altValues[$alt->id][$c->id] = 0;
                foreach ($alt->values as $val) {
                    $sub = $val->subCriteria;
                    if ($sub && $sub->criteria_id === $c->id) {
                        $altValues[$alt->id][$c->id] = $sub->value;
                        break;
                    }
                }
            }
        }

        // Normalisasi nilai alternatif per kriteria (sqrt(sum^2))
        $normDivisor = [];
        foreach ($criteria as $c) {
            $sumSquares = 0;
            foreach ($alternatives as $alt) {
                $val = $altValues[$alt->id][$c->id] ?? 0;
                $sumSquares += pow($val, 2);
            }
            $normDivisor[$c->id] = sqrt($sumSquares) ?: 1;
        }

        // Normalisasi dan perhitungan MOORA
        $normalization = [];
        $valueMoora = [];

        foreach ($alternatives as $alt) {
            $benefit = 0;
            $cost = 0;

            foreach ($criteria as $c) {
                $raw = $altValues[$alt->id][$c->id] ?? 0;
                $norm = $raw / $normDivisor[$c->id];
                $weighted = $norm * $weight[$c->id];

                $normalization[$alt->id][$c->id] = $weighted;

                if (strtolower($c->attribute_type) === 'benefit') {
                    $benefit += $weighted;
                } else {
                    $cost += $weighted;
                }
            }

            $valueMoora[$alt->id] = $benefit - $cost;
        }

        // Urutkan berdasarkan nilai MOORA tertinggi ke terendah
        arsort($valueMoora);
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.moora.pdf_report', compact(
            'alternatives',
            'criteria',
            'normalization',
            'weight',
            'valueMoora',
            'normDivisor',
            'productSelected',
            'altValues',
            'products'
        ));
        
        return $pdf->download('laporan-moora.pdf');
    }
}
