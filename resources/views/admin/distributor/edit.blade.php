@extends('layouts.app')

@section('title', 'Distributor Barang Elektrikal')

@section('content')

    <x-alert />

    <div class="card">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Ubah Data Distributor</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('distributor.update', $distributor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Distributor</label>
                    <input required type="text" name="name" class="form-control" placeholder="Masukkan nama distributor"
                        value="{{ old('name', $distributor->name) }}" />
                </div>
                <div class="form-group">
                    <label for="image_name">Logo Perusahaan</label>
                    @if($distributor->image_name)
                        <img src="{{ asset('storage/distributor/' . $distributor->image_name) }}" width="100" alt="" class="mb-2 ml-2">
                    @endif
                    <input type="file" name="image_name" class="form-control" />
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah logo</small>
                </div>
                <div class="form-group">
                    <label for="company_name">Nama Perusahaan</label>
                    <input required type="text" name="company_name" class="form-control" placeholder="Masukkan nama perusahaan"
                        value="{{ old('company_name', $distributor->company_name) }}" />
                </div>
                <div class="form-group">
                    <label for="address">Alamat</label>
                    <textarea required class="form-control" name="address" placeholder="Masukkan alamat lengkap">{{ old('address', $distributor->address) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="phone">Telepon</label>
                    <input required type="text" name="phone" class="form-control" placeholder="Masukkan nomor telepon"
                        value="{{ old('phone', $distributor->phone) }}" />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input required type="email" name="email" class="form-control" placeholder="Masukkan alamat email"
                        value="{{ old('email', $distributor->email) }}" />
                </div>
                <div class="form-group">
                    <label for="product_category_id">Kategori Produk</label>
                    <select required class="form-control" name="product_category_id" id="product_category_id">
                        <option hidden>Pilih kategori produk</option>
                        @foreach ($productCategories as $category)
                            <option value="{{ $category->id }}" {{ $distributor->product_category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment_term_id">Termin Pembayaran</label>
                    <select required class="form-control" name="payment_term_id" id="payment_term_id">
                        <option hidden>Pilih termin pembayaran</option>
                        @foreach ($paymentTerms as $term)
                            <option value="{{ $term->id }}" {{ $distributor->payment_term_id == $term->id ? 'selected' : '' }}>
                                {{ $term->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="delivery_method_id">Metode Pengiriman</label>
                    <select required class="form-control" name="delivery_method_id" id="delivery_method_id">
                        <option hidden>Pilih metode pengiriman</option>
                        @foreach ($deliveryMethods as $method)
                            <option value="{{ $method->id }}" {{ $distributor->delivery_method_id == $method->id ? 'selected' : '' }}>
                                {{ $method->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="business_scale_id">Skala Bisnis</label>
                    <select required class="form-control" name="business_scale_id" id="business_scale_id">
                        <option hidden>Pilih skala bisnis</option>
                        @foreach ($businessScales as $scale)
                            <option value="{{ $scale->id }}" {{ $distributor->business_scale_id == $scale->id ? 'selected' : '' }}>
                                {{ $scale->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Deskripsi Perusahaan</label>
                    <textarea class="form-control" name="description" placeholder="Masukkan deskripsi perusahaan" id="description">{{ old('description', $distributor->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="is_active">Status</label>
                    <select required class="form-control" name="is_active" id="is_active">
                        <option hidden>Pilih status</option>
                        <option value="0" {{ $distributor->is_active == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="1" {{ $distributor->is_active ? 'selected' : '' }}>Aktif</option>
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

@endsection