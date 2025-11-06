@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-box text-primary mr-2"></i>Produk
        </h1>
        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-eye mr-2"></i>Detail Produk
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Informasi Utama -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="text-center mb-4">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-box fa-2x text-white"></i>
                                </div>
                                <h4 class="font-weight-bold text-primary">{{ $product->name }}</h4>
                                @if($product->description)
                                <p class="text-muted mb-0">{{ $product->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Detail Information -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-dark mb-3 border-bottom pb-2">
                                        <i class="fas fa-info-circle mr-2"></i>Informasi Produk
                                    </h6>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold text-dark" style="width: 150px;">
                                                        <i class="fas fa-tag text-primary mr-2"></i>Nama
                                                    </td>
                                                    <td class="text-dark">{{ $product->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-file-alt text-success mr-2"></i>Deskripsi
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $product->description ?: '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-calendar-plus text-info mr-2"></i>Dibuat
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $product->created_at->format('d F Y H:i') }}
                                                        <small class="text-muted">({{ $product->created_at->diffForHumans() }})</small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-calendar-check text-warning mr-2"></i>Diupdate
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $product->updated_at->format('d F Y H:i') }}
                                                        <small class="text-muted">({{ $product->updated_at->diffForHumans() }})</small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-dark mb-3 border-bottom pb-2">
                                        <i class="fas fa-truck text-warning mr-2"></i>Distributor Penyedia
                                        <span class="badge badge-primary ml-2">{{ $product->distributors->count() }}</span>
                                    </h6>
                                    
                                    @if($product->distributors->count() > 0)
                                        <div class="distributor-list">
                                            @foreach($product->distributors as $distributor)
                                                <div class="distributor-item mb-3 p-3 border rounded">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="font-weight-bold text-dark mb-1">
                                                                <i class="fas fa-warehouse text-primary mr-2"></i>
                                                                {{ $distributor->name }}
                                                            </h6>
                                                            @if($distributor->address)
                                                            <p class="text-muted small mb-1">
                                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                                {{ Str::limit($distributor->address, 50) }}
                                                            </p>
                                                            @endif
                                                            @if($distributor->phone)
                                                            <p class="text-muted small mb-0">
                                                                <i class="fas fa-phone mr-1"></i>
                                                                {{ $distributor->phone }}
                                                            </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
                                            <p class="mb-0">Belum ada distributor yang menyediakan produk ini</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.product.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                                </a>
                                <div>
                                    @if (auth()->user()->is_admin == 1)
                                    <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary btn-lg mr-2">
                                        <i class="fas fa-edit mr-2"></i>Edit
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #047857 0%, #0adf9b 100%) !important;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.btn-lg {
    border-radius: 8px;
    padding: 0.75rem 2rem;
}

.table-borderless td {
    border: none !important;
    padding: 0.75rem 0.5rem;
}

.distributor-item {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.distributor-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.distributor-list {
    max-height: 400px;
    overflow-y: auto;
}
</style>
@endsection