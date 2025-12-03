@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Selamat Datang <span class="font-weight-bold text-primary">{{ auth()->user()->name }}</span>!</h1>
    <div class="text-muted jancok">
        <i class="fas fa-calendar-alt mr-1"></i>
        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4 jancok">
    <!-- Main Metrics -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="border-left: 4px solid #059669 !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="font-weight-bold text-primary text-uppercase mb-1">Total Distributor</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $data->distributors }}</div>
                        <div class="mt-2 text-success">
                            <i class="fas fa-warehouse mr-1"></i>
                            <span class="">Data Supplier</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2" style="border-left: 4px solid #10b981 !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="font-weight-bold text-success text-uppercase mb-1">Total Kriteria</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $data->criteria }}</div>
                        <div class="mt-2 text-info">
                            <i class="fas fa-list-alt mr-1"></i>
                            <span class="">Parameter Penilaian</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="font-weight-bold text-info text-uppercase mb-1">Total Alternatif</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $data->alternative }}</div>
                        <div class="mt-2 text-primary">
                            <i class="fas fa-th mr-1"></i>
                            <span class="">Opsi Evaluasi</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-th fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('admin')
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="font-weight-bold text-warning text-uppercase mb-1">Total User</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $data->users }}</div>
                        <div class="mt-2 text-warning">
                            <i class="fas fa-users mr-1"></i>
                            <span class="">Pengguna Sistem</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>

<div class="row jancok">
    <!-- Master Data Section -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h3 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-database mr-2"></i>Data Master Sistem
                </h3>
                <span class="badge badge-primary">Overview</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="font-weight-bold text-dark mb-1">
                                            <i class="fas fa-chart-line text-success mr-2"></i>Skala Bisnis
                                        </h3>
                                        <p class="text-muted mb-0">Klasifikasi ukuran distributor</p>
                                    </div>
                                    <span class="h4 font-weight-bold text-success">{{ $data->businessScales }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="font-weight-bold text-dark mb-1">
                                            <i class="fas fa-shipping-fast text-info mr-2"></i>Metode Pengiriman
                                        </h3>
                                        <p class="text-muted mb-0">Cara pengiriman barang</p>
                                    </div>
                                    <span class="h4 font-weight-bold text-info">{{ $data->deliveryMethods }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="font-weight-bold text-dark mb-1">
                                            <i class="fas fa-boxes text-warning mr-2"></i>Produk
                                        </h3>
                                        <p class="text-muted mb-0">Jenis barang elektrikal</p>
                                    </div>
                                    <span class="h4 font-weight-bold text-warning">{{ $data->product }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="font-weight-bold text-dark mb-1">
                                            <i class="fas fa-money-bill-wave text-danger mr-2"></i>Termin Pembayaran
                                        </h3>
                                        <p class="text-muted mb-0">Sistem pembayaran</p>
                                    </div>
                                    <span class="h4 font-weight-bold text-danger">{{ $data->paymentTerms }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4 jancok">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                </h3>
            </div>
            <div class="card-body jancok">
                <div class="d-grid gap-2 jancok">
                    <a href="{{ route('distributor.index') }}" class="btn btn-primary btn-block text-left jancok">
                        <i class="fas fa-warehouse mr-2"></i>Lihat Distributor
                    </a>
                    <a href="{{ route('distributor.compare.form') }}" class="btn btn-success btn-block text-left jancok">
                        <i class="fas fa-balance-scale mr-2"></i>Bandingkan Distributor
                    </a>
                    @cannot('admin')
                    <a href="{{ route('calculation.user') }}" class="btn btn-info btn-block text-left jancok">
                        <i class="fas fa-bullseye mr-2"></i>Dapatkan Rekomendasi
                    </a>
                    @endcannot
                    @can('admin')
                    <a href="{{ route('moora.calculation') }}" class="btn btn-warning btn-block text-left jancok">
                        <i class="fas fa-calculator mr-2"></i>Hitung MOORA
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Sistem
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="img-fluid rounded-circle" style="width: 80px; height: 80px;">
                </div>
                <h3 class="text-center text-primary font-weight-bold">SPK MOORA</h3>
                <p class="text-center text-muted small mb-3">Sistem Pendukung Keputusan</p>
                
                <div class="system-info">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Metode:</span>
                        <span class="font-weight-bold">MOORA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Fungsi:</span>
                        <span class="font-weight-bold text-success">Rekomendasi Distributor</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Bidang:</span>
                        <span class="font-weight-bold">Barang Elektrikal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
    border: none;
    border-radius: 10px;
}

.card:hover {
    transform: translateY(-5px);
}

.border-left-primary { border-left-color: #059669 !important; }
.border-left-success { border-left-color: #10b981 !important; }
.border-left-info { border-left-color: #3b82f6 !important; }
.border-left-warning { border-left-color: #f59e0b !important; }

.btn-block.text-left { text-align: left !important; }
.d-grid.gap-2 { gap: 10px; }

.system-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #059669;
}
.jancok {
    font-size: 30px !important;
}
</style>

@endsection