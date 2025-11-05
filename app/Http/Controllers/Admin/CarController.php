<?php

namespace App\Http\Controllers\Admin;

use App\Models\Car;
use App\Http\Controllers\Controller;
use App\Models\TransmissionType;
use App\Http\Requests\Admin\CarRequest;
use App\Models\CarBrand;
use App\Models\CarType;
use App\Models\FuelType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CarController extends Controller
{
    public function index(): View
    {
        $cars = Car::latest()->get();

        $cars->transform(function ($car) {
            return [
                'id' => $car->id,
                'image' => '<a href="#" data-toggle="modal" data-target="#imageModal" onclick="showImage(\'' . $car->name . '\', \'' . asset('storage/car/' . ($car->image_name ?? 'default-image.png')) . '\')">
                                <img class="default-img" src="' . asset('storage/car/' . ($car->image_name ?? 'default-image.png')) . '" width="60">
                            </a>',
                'name' => $car->name,
                'price' => 'Rp ' . number_format($car->price, 0, ',', '.'),
                'manufacture_year' => $car->manufacture_year,
                'mileage' => number_format($car->mileage, 0, ',', '.') . ' Kilometer',
                'fuel_type' => $car->fuelType?->name ?? 'N/A',
                'engine_capacity' => $car->engine_capacity . ' cc',
                'seat_count' => $car->seat_count,
                'transmission_type' => $car->transmissionType?->name ?? 'N/A',
                'color' => $car->color,
                'is_available' => $car->is_available ? 'Tersedia' : 'Tidak Tersedia',
            ];
        });

        return view('admin.car.index', compact('cars'));
    }

    public function create(): View
    {
        $brands = CarBrand::all();
        $carTypes = CarType::all();
        $fuelTypes = FuelType::all();
        $transmissionTypes = TransmissionType::all();

        return view('admin.car.create', compact('brands', 'carTypes', 'fuelTypes', 'transmissionTypes'));
    }

    public function store(CarRequest $request): RedirectResponse
    {
        if ($request->validated()) {
            $image = $request->file('image_name')->store('car', 'public');
            $imageName = basename($image);

            Car::create($request->except('image_name') + ['image_name' => $imageName]);
        }

        return redirect()->route('car.index')->with('success', 'Data berhasil disimpan');
    }

    public function show($id)
    {
        $car = Car::with(['carBrand', 'carType', 'fuelType', 'transmissionType'])->findOrFail($id);
        return view('admin.car.show', compact('car'));
    }

    public function showComparisonForm()
    {
        $cars = Car::all();

        return view('admin.car.compare_form', compact('cars'));
    }

    public function compare(Request $request)
    {
        if (!$request->car1) {
            return redirect()->route('car.compare.form')->with('error', 'Mobil pertama wajib dipilih');
        }

        if (!$request->car2) {
            return redirect()->route('car.compare.form')->with('error', 'Mobil kedua wajib dipilih');
        }

        $request->validate([
            'car1' => 'required|exists:cars,id',
            'car2' => 'required|exists:cars,id',
        ]);

        $car1 = Car::with(['carBrand', 'carType', 'fuelType', 'transmissionType'])->findOrFail($request->car1);
        $car2 = Car::with(['carBrand', 'carType', 'fuelType', 'transmissionType'])->findOrFail($request->car2);

        return view('admin.car.compare', compact('car1', 'car2'));
    }

    public function edit($id)
    {
        $car = Car::findOrFail($id);
        $brands = CarBrand::all();
        $carTypes = CarType::all();
        $fuelTypes = FuelType::all();
        $transmissionTypes = TransmissionType::all();

        return view('admin.car.edit', compact('car', 'brands', 'carTypes', 'fuelTypes', 'transmissionTypes'));
    }

    public function update(CarRequest $request, Car $car): RedirectResponse
    {
        if ($request->validated()) {
            $dataUpdate = $request->except('image_name');

            if ($request->hasFile('image_name')) {
                if ($car->image_name) {
                    Storage::delete('public/car/' . $car->image_name);
                }

                $image = $request->file('image_name')->store('car', 'public');
                $imageName = basename($image);

                $dataUpdate['image_name'] = $imageName;
            }

            $car->update($dataUpdate);
        }

        return redirect()->route('car.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(Car $car): RedirectResponse
    {
        if ($car->image_name) {
            unlink('storage/car/' . $car->image_name);
        }
        $car->delete();
        return redirect()->route('car.index')->with('success', 'Data berhasil dihapus');
    }
}
