<!-- resources/views/admin/distributor/compare.blade.php -->
@extends('layouts.app')

@section('title', 'Bandingkan Dua Distributor')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-balance-scale text-primary mr-2"></i>Perbandingan Distributor
        </h1>
        <a href="{{ route('distributor.compare.form') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row">
        <!-- Distributor 1 -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fas fa-warehouse mr-2"></i>{{ $distributor1->name }}
                        </h5>
                        <span class="badge badge-light">Distributor 1</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Logo & Basic Info -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $distributor1->image_name ? asset('storage/distributor/' . $distributor1->image_name) : asset('img/default-image.png') }}" 
                                 class="img-fluid rounded-circle shadow" 
                                 alt="{{ $distributor1->name }}" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                            <span class="position-absolute top-0 start-100 translate-middle badge badge-success badge-lg">
                                <i class="fas fa-check"></i>
                            </span>
                        </div>
                        <h4 class="mt-3 font-weight-bold text-dark">{{ $distributor1->company_name }}</h4>
                        <span class="badge badge-pill {{ $distributor1->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $distributor1->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>

                    <!-- Comparison Highlights -->
                    <div class="comparison-highlights mb-4">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <i class="fas fa-boxes fa-lg text-primary mb-2"></i>
                                    <h6 class="font-weight-bold mb-1">{{ $distributor1->productCategory?->name ?? '-' }}</h6>
                                    <small class="text-muted">Kategori</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <i class="fas fa-shipping-fast fa-lg text-success mb-2"></i>
                                    <h6 class="font-weight-bold mb-1">{{ $distributor1->deliveryMethod?->name ?? '-' }}</h6>
                                    <small class="text-muted">Pengiriman</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <i class="fas fa-chart-line fa-lg text-warning mb-2"></i>
                                    <h6 class="font-weight-bold mb-1">{{ $distributor1->businessScale?->name ?? '-' }}</h6>
                                    <small class="text-muted">Skala</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Info -->
                    <x-table_distributor :distributor="$distributor1" />
                </div>
            </div>
        </div>

        <!-- Distributor 2 -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-gradient-info text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fas fa-warehouse mr-2"></i>{{ $distributor2->name }}
                        </h5>
                        <span class="badge badge-light">Distributor 2</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Logo & Basic Info -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $distributor2->image_name ? asset('storage/distributor/' . $distributor2->image_name) : asset('img/default-image.png') }}" 
                                 class="img-fluid rounded-circle shadow" 
                                 alt="{{ $distributor2->name }}" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                            <span class="position-absolute top-0 start-100 translate-middle badge badge-success badge-lg">
                                <i class="fas fa-check"></i>
                            </span>
                        </div>
                        <h4 class="mt-3 font-weight-bold text-dark">{{ $distributor2->company_name }}</h4>
                        <span class="badge badge-pill {{ $distributor2->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $distributor2->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>

                    <!-- Comparison Highlights -->
                    <div class="comparison-highlights mb-4">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <i class="fas fa-boxes fa-lg text-primary mb-2"></i>
                                    <h6 class="font-weight-bold mb-1">{{ $distributor2->productCategory?->name ?? '-' }}</h6>
                                    <small class="text-muted">Kategori</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <i class="fas fa-shipping-fast fa-lg text-success mb-2"></i>
                                    <h6 class="font-weight-bold mb-1">{{ $distributor2->deliveryMethod?->name ?? '-' }}</h6>
                                    <small class="text-muted">Pengiriman</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <i class="fas fa-chart-line fa-lg text-warning mb-2"></i>
                                    <h6 class="font-weight-bold mb-1">{{ $distributor2->businessScale?->name ?? '-' }}</h6>
                                    <small class="text-muted">Skala</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Info -->
                    <x-table_distributor :distributor="$distributor2" />
                </div>
            </div>
        </div>
    </div>

    <!-- Comparison Summary -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-warning text-dark py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-chart-bar mr-2"></i>Ringkasan Perbandingan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-trophy mr-2"></i>Kelebihan {{ $distributor1->name }}
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    <strong>Perusahaan:</strong> {{ $distributor1->company_name }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    <strong>Kategori:</strong> {{ $distributor1->productCategory?->name ?? '-' }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    <strong>Skala Bisnis:</strong> {{ $distributor1->businessScale?->name ?? '-' }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-info mb-3">
                                <i class="fas fa-trophy mr-2"></i>Kelebihan {{ $distributor2->name }}
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    <strong>Perusahaan:</strong> {{ $distributor2->company_name }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    <strong>Kategori:</strong> {{ $distributor2->productCategory?->name ?? '-' }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    <strong>Skala Bisnis:</strong> {{ $distributor2->businessScale?->name ?? '-' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.badge-lg {
    padding: 0.4rem 0.6rem;
    font-size: 0.8rem;
}

.comparison-highlights .border {
    border: 1px solid #e3e6f0 !important;
    transition: all 0.3s ease;
}

.comparison-highlights .border:hover {
    border-color: #059669 !important;
    transform: translateY(-2px);
}

.position-relative .badge {
    transform: translate(-50%, -50%);
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1) !important;
}
</style>
@endsection