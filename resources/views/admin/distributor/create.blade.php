@extends('layouts.app')

@section('title', 'Distributor Barang Elektrikal')

@section('content')

    <x-alert />

    <div class="card">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Tambah Data Distributor</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('distributor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Distributor</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama distributor"
                        value="{{ old('name') }}" required />
                </div>
                <div class="form-group">
                    <label for="image_name">Logo Perusahaan</label>
                    <input type="file" name="image_name" id="image_name" class="form-control" accept="image/*"
                        onchange="previewImage(event)" />
                    <img id="imagePreview" class="img-fluid mt-2" style="max-width: 300px; display: none;" />
                    <small class="form-text text-muted">Upload logo perusahaan (opsional)</small>
                </div>
                <div class="form-group">
                    <label for="company_name">Nama Perusahaan</label>
                    <input type="text" name="company_name" class="form-control" placeholder="Masukkan nama perusahaan"
                        value="{{ old('company_name') }}" required />
                </div>
                <div class="form-group">
                    <label for="address">Alamat</label>
                    <textarea class="form-control" name="address" placeholder="Masukkan alamat lengkap" required>{{ old('address') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="phone">Telepon</label>
                    <input type="text" name="phone" class="form-control" placeholder="Masukkan nomor telepon"
                        value="{{ old('phone') }}" required />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan alamat email"
                        value="{{ old('email') }}" required />
                </div>
                <div class="form-group">
                    <label for="product_category_id">Kategori Produk</label>
                    <select class="form-control" name="product_category_id" id="product_category_id" required>
                        <option value="" hidden>Pilih kategori produk</option>
                        @foreach ($productCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment_term_id">Termin Pembayaran</label>
                    <select class="form-control" name="payment_term_id" id="payment_term_id" required>
                        <option value="" hidden>Pilih termin pembayaran</option>
                        @foreach ($paymentTerms as $term)
                            <option value="{{ $term->id }}">{{ $term->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="delivery_method_id">Metode Pengiriman</label>
                    <select class="form-control" name="delivery_method_id" id="delivery_method_id" required>
                        <option value="" hidden>Pilih metode pengiriman</option>
                        @foreach ($deliveryMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="business_scale_id">Skala Bisnis</label>
                    <select class="form-control" name="business_scale_id" id="business_scale_id" required>
                        <option value="" hidden>Pilih skala bisnis</option>
                        @foreach ($businessScales as $scale)
                            <option value="{{ $scale->id }}">{{ $scale->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Deskripsi Perusahaan</label>
                    <textarea class="form-control" name="description" placeholder="Masukkan deskripsi perusahaan" id="description">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="is_active">Status</label>
                    <select class="form-control" name="is_active" id="is_active" required>
                        <option value="" hidden>Pilih status</option>
                        <option value="0">Tidak Aktif</option>
                        <option value="1">Aktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <a href="{{ route('distributor.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
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