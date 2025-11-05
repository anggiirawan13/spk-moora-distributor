@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    <div class="card">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Ubah Data Kategori Produk</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.product_category.update', $productCategory->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama Kategori Produk</label>
                    <input type="text" class="form-control" name="name" value="{{ $productCategory->name }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="description" rows="3">{{ $productCategory->description }}</textarea>
                    <small class="form-text text-muted">Deskripsi singkat tentang kategori produk</small>
                </div>

                <div class="form-group">
                    <x-button_back route="admin.product_category.index" />
                    <x-button_save />
                </div>
            </form>
        </div>
    </div>

@endsection