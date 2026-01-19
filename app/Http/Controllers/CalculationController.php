<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Criteria;
use App\Models\Alternative;
use App\Models\Product;

class CalculationController extends Controller
{
    public function calculation(Request $request)
    {
        $products = Product::withCount('distributors')->get();

        $alternatives = null;
        $productSelected = null;

        if ($request->has('product_id') && $request->product_id) {
            $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);

            $productId = $request->product_id;
            $productSelected = Product::findOrFail($productId);

            $distributors = $productSelected->distributors;
            $alternatives = Alternative::whereIn('distributor_id', $distributors->pluck('id'))->get();

            if ($alternatives->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data alternatif untuk distributor yang menyediakan produk ini.')->with('products', $products);
            }
        } else {
            $alternatives = Alternative::all();

            if ($alternatives->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data alternatif yang tersedia.')->with('products', $products);
            }
        }

        $criteria = Criteria::with(['subCriteria'])->get();

        $alternatives->load(['values.subCriteria', 'distributor']);

        $totalWeight = $criteria->sum('weight') ?: 1;
        $weight = $criteria->pluck('weight', 'id')->map(fn($w) => $w / $totalWeight);

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

        $normDivisor = [];
        foreach ($criteria as $c) {
            $sumSquares = 0;
            foreach ($alternatives as $alt) {
                $val = $altValues[$alt->id][$c->id] ?? 0;
                $sumSquares += pow($val, 2);
            }
            $normDivisor[$c->id] = sqrt($sumSquares) ?: 1;
        }

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

        arsort($valueMoora);

        return view('moora.calculation', compact(
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
    }

    public function downloadPDF(Request $request)
    {
        $products = Product::withCount('distributors')->get();

        $alternatives = null;
        $productSelected = null;

        if ($request->product_id) {
            $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);

            $productId = $request->product_id;
            $productSelected = Product::findOrFail($productId);

            $distributors = $productSelected->distributors;
            $alternatives = Alternative::whereIn('distributor_id', $distributors->pluck('id'))->get();

            if ($alternatives->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data alternatif untuk distributor yang menyediakan produk ini.')->with('products', $products);
            }
        } else {
            $alternatives = Alternative::all();

            if ($alternatives->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data alternatif yang tersedia.')->with('products', $products);
            }
        }

        $criteria = Criteria::with(['subCriteria'])->get();

        $alternatives->load(['values.subCriteria', 'distributor']);

        $totalWeight = $criteria->sum('weight') ?: 1;
        $weight = $criteria->pluck('weight', 'id')->map(fn($w) => $w / $totalWeight);

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

        $normDivisor = [];
        foreach ($criteria as $c) {
            $sumSquares = 0;
            foreach ($alternatives as $alt) {
                $val = $altValues[$alt->id][$c->id] ?? 0;
                $sumSquares += pow($val, 2);
            }
            $normDivisor[$c->id] = sqrt($sumSquares) ?: 1;
        }

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

        arsort($valueMoora);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('moora.pdf_report', compact(
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
