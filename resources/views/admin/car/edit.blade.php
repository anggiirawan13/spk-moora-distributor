@extends('layouts.app')

@section('title', 'Mobil Bekas')

@section('content')

    <x-alert />

    <div class="card">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Ubah Data Mobil Bekas</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('car.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Mobil</label>
                    <input required type="text" name="name" class="form-control" placeholder="Masukkan nama car"
                        value="{{ old('name', $car->name) }}" wire:model="name" wire:keyup="generateSlug" />
                </div>
                <div class="form-group">
                    <label for="image_name">Foto Mobil</label>
                    <img src="{{ asset('storage/car/' . $car->image_name) }}" width="100" alt="" class="mb-2 ml-2">
                    <input type="file" name="image_name" class="form-control" wire:model="image_name" />
                </div>
                <div class="form-group">
                    <label for="price">Harga (Rp)</label>
                    <input required ="number" name="price" class="form-control" placeholder="Masukkan price dalam rupiah"
                        value="{{ old('price', $car->price) }}" />
                </div>
                <div class="form-group">
                    <label for="manufacture_year">Tahun Produksi</label>
                    <input required ="number" name="manufacture_year" class="form-control"
                        placeholder="Masukkan manufacture_year produksi"
                        value="{{ old('manufacture_year', $car->manufacture_year) }}" />
                </div>
                <div class="form-group">
                    <label for="brand_id">Merek Mobil</label>
                    <select required class="form-control" name="brand_id" id="brand_id">
                        <option hidden>Pilih merek mobil</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $car->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="mileage">Jarak Tempuh (km)</label>
                    <input required type="text" name="mileage" class="form-control"
                        placeholder="Masukkan jarak tempuh dalam km" value="{{ old('mileage', $car->mileage) }}" />
                </div>
                <div class="form-group">
                    <label for="fuel_type_id">Bahan Bakar</label>
                    <select required class="form-control" name="fuel_type_id" id="fuel_type_id">
                        <option hidden>Pilih bahan bakar</option>
                        @foreach ($fuelTypes as $fuelType)
                            <option value="{{ $fuelType->id }}"
                                {{ $car->fuel_type_id == $fuelType->id ? 'selected' : '' }}>
                                {{ $fuelType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="engine_capacity">Kapasitas Mesin (cc)</label>
                    <input required type="text" name="engine_capacity" class="form-control"
                        placeholder="Masukkan kapasitas mesin dalam cc"
                        value="{{ old('engine_capacity', $car->engine_capacity) }}" />
                </div>
                <div class="form-group">
                    <label for="car_type_id">Jenis Mobil</label>
                    <select required class="form-control" name="car_type_id" id="car_type_id">
                        <option hidden>Pilih jenis mobil</option>
                        @foreach ($carTypes as $carType)
                            <option value="{{ $carType->id }}" {{ $car->car_type_id == $carType->id ? 'selected' : '' }}>
                                {{ $carType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="seat_count">Jumlah Kursi</label>
                    <input required type="text" name="seat_count" class="form-control"
                        placeholder="Masukkan jumlah kursi" value="{{ old('seat_count', $car->seat_count) }}" />
                </div>
                <div class="form-group">
                    <label for="transmission_type_id">Transmisi</label>
                    <select required class="form-control" name="transmission_type_id" id="transmission_type_id">
                        <option hidden>Pilih transmisi</option>
                        @foreach ($transmissionTypes as $transmissionType)
                            <option value="{{ $transmissionType->id }}"
                                {{ $car->transmission_type_id == $transmissionType->id ? 'selected' : '' }}>
                                {{ $transmissionType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="color">Warna Mobil</label>
                    <input required type="text" name="color" class="form-control" placeholder="Masukkan color car"
                        value="{{ old('color', $car->color) }}" />
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi Mobil</label>
                    <textarea required class="form-control" name="description" placeholder="Masukkan description car" id="description">{{ old('description', $car->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="is_available">Ketersediaan</label>
                    <select required class="form-control" name="is_available" id="is_available">
                        <option hidden>Pilih status is_available</option>
                        <option {{ $car->is_available == 0 ? 'selected' : '' }} value="0">Tidak Tersedia</option>
                        <option {{ $car->is_available == 1 ? 'selected' : '' }} value="1">Tersedia</option>
                    </select>
                </div>
                <div class="form-group">
                    <a href="{{ route('car.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                        Kembali</a>
                    <x-button_save />
                </div>
            </form>
        </div>
    </div>

@endsection
