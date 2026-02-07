@extends('layouts.app')

@section('title', 'Import Excel')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-excel text-success mr-2"></i>Import Excel
        </h1>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0 font-weight-bold">
                <i class="fas fa-upload mr-2"></i>Upload File Excel
            </h5>
        </div>
        <div class="card-body p-4">
            <x-alert />

            <div class="mb-3">
                <a class="btn btn-outline-success" href="{{ route('import.excel.template') }}">
                    <i class="fas fa-download mr-2"></i>Download Template
                </a>
            </div>

            @if (session('import_stats'))
                <div class="alert alert-info">
                    <div class="mb-2 font-weight-bold">
                        <i class="fas fa-info-circle mr-1"></i>Ringkasan Import Terakhir
                    </div>
                    <ul class="list-unstyled mb-0">
                        @foreach (session('import_stats') as $sheet => $stat)
                            <li>
                                <strong>{{ $sheet }}</strong>:
                                created {{ $stat['created'] ?? 0 }},
                                skipped {{ $stat['skipped'] ?? 0 }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('import.excel.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file" class="font-weight-bold text-dark mb-2">
                        <i class="fas fa-file-upload text-primary mr-2"></i>File Excel (xlsx/xls)
                    </label>
                    <input type="file" 
                           class="form-control @error('file') is-invalid @enderror" 
                           id="file" 
                           name="file" 
                           accept=".xlsx,.xls"
                           required>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-search mr-2"></i>Preview Import
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow border-0 mt-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-info-circle mr-2"></i>Informasi
            </h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle mr-1"></i>
                Data yang sudah ada tidak akan di-update dan akan dicatat di file error. Kolom `code` akan otomatis di-uppercase saat import.
            </div>
            <div class="alert alert-warning mt-3 mb-0">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Urutan sheet wajib: Skala Bisnis, Metode Pengiriman, Termin Pembayaran, Distributor, Produk, Distributor Produk, Kriteria, Sub Kriteria, Alternatif.
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
}
</style>
@endsection
