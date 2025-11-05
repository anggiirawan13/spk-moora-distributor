@extends('layouts.app')

@section('title', 'Mobil Bekas')

@section('content')

    <x-alert />

    <div class="card">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Tambah Data Mobil Bekas</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('car.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
 
                <div class="form-group">
                    <label for="name">Nama Mobil</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama mobil"
                        value="{{ old('name') }}" wire:model="name" wire:keyup="name" required />
                </div>
                <div class="form-group">
                    <label for="image_name">Foto Mobil</label>
                    <input type="file" name="image_name" id="image_name" class="form-control" accept="image/*" required
                        onchange="previewImage(event)" />
                    <img id="imagePreview" class="img-fluid mt-2" style="max-width: 300px; display: none;" />
                </div>
                <div class="form-group">
                    <label for="price">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" placeholder="Masukkan harga dalam rupiah"
                        value="{{ old('price') }}" required min="1" />
                </div>
                <div class="form-group">
                    <label for="manufacture_year">Tahun Produksi</label>
                    <input type="number" name="manufacture_year" class="form-control" placeholder="Masukkan tahun produksi"
                        value="{{ old('manufacture_year') }}" required min="2000" />
                </div>
                <div class="form-group">
                    <label for="brand_id">Merek Mobil</label>
                    <select class="form-control" name="brand_id" id="brand_id" required>
                        <option value="" hidden>Pilih merek mobil</option>
                        @foreach ($brands as $carBrand)
                            <option value="{{ $carBrand->id }}">{{ $carBrand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="mileage">Jarak Tempuh (km)</label>
                    <input type="number" name="mileage" class="form-control" placeholder="Masukkan jarak tempuh dalam km"
                        min="0" value="{{ old('mileage') }}" required />
                </div>
                <div class="form-group">
                    <label for="fuel_type_id">Bahan bakar</label>
                    <select class="form-control" name="fuel_type_id" id="fuel_type_id" required>
                        <option value="" hidden>Pilih bahan bakar</option>
                        @foreach ($fuelTypes as $fuelType)
                            <option value="{{ $fuelType->id }}">{{ $fuelType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="engine_capacity">Kapasitas Mesin (cc)</label>
                    <input type="number" name="engine_capacity" class="form-control"
                        placeholder="Masukkan kapasitas mesin dalam cc" value="{{ old('engine_capacity') }}" required />
                </div>
                <div class="form-group">
                    <label for="car_type_id">Jenis Mobil</label>
                    <select class="form-control" name="car_type_id" id="car_type_id" required>
                        <option value="" hidden>Pilih jenis mobil</option>
                        @foreach ($carTypes as $carType)
                            <option value="{{ $carType->id }}">{{ $carType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="seat_count">Jumlah Kursi</label>
                    <input type="number" name="seat_count" class="form-control" placeholder="Masukkan jumlah kursi"
                        value="{{ old('seat_count') }}" required min="2" />
                </div>
                <div class="form-group">
                    <label for="transmission_type_id">Transmisi</label>
                    <select class="form-control" name="transmission_type_id" id="transmission_type_id" required>
                        <option value="" hidden>Pilih transmisi</option>
                        @foreach ($transmissionTypes as $transmission)
                            <option value="{{ $transmission->id }}">{{ $transmission->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="color">Warna Mobil</label>
                    <input type="text" name="color" class="form-control" placeholder="Masukkan color car"
                        value="{{ old('color') }}" required />
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi Mobil</label>
                    <textarea class="form-control" name="description" placeholder="Masukkan deskripsi mobil" id="description" required>{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="is_available">Ketersediaan</label>
                    <select class="form-control" name="is_available" id="is_available" required>
                        <option value="" hidden>Pilih status ketersediaan</option>
                        <option value="0">Tidak Tersedia</option>
                        <option value="1">Tersedia</option>
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

    <script>
        function previewImage(event) {
            var input = event.target;
            var reader = new FileReader();

            reader.onload = function() {
                var imgElement = document.getElementById('imagePreview');
                imgElement.src = reader.result;
                imgElement.style.display = 'block'; // Tampilkan preview image
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@endsection
