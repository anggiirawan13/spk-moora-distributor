@extends('layouts.app')

@section('title', 'Detail Sub Kriteria')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-layer-group text-primary mr-2"></i>Sub Kriteria
        </h1>
        <a href="{{ route('admin.subcriteria.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Daftar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-eye mr-2"></i>Detail Sub Kriteria
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Header Info -->
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <div class="subcriteria-header">
                                <div class="icon-container bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-layer-group fa-2x text-white"></i>
                                </div>
                                <h3 class="font-weight-bold text-primary mb-2">{{ $subCriteria->name }}</h3>
                                <div class="status-badges">
                                    <span class="badge badge-success badge-pill mr-2">
                                        <i class="fas fa-hashtag mr-1"></i>Nilai: {{ $subCriteria->value }}
                                    </span>
                                    <span class="badge {{ $subCriteria->is_active ? 'badge-success' : 'badge-secondary' }} badge-pill">
                                        <i class="fas fa-circle mr-1"></i>
                                        {{ $subCriteria->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kriteria Induk Info -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-left-primary">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 font-weight-bold text-primary">
                                        <i class="fas fa-list-alt mr-2"></i>Kriteria Induk
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <strong class="text-dark">Kode:</strong>
                                                <div class="mt-1">
                                                    <span class="badge badge-primary badge-pill">
                                                        {{ $subCriteria->criteria->code }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <strong class="text-dark">Nama:</strong>
                                                <div class="mt-1 text-dark">{{ $subCriteria->criteria->name }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <strong class="text-dark">Jenis:</strong>
                                                <div class="mt-1">
                                                    <span class="badge {{ $subCriteria->criteria->attribute_type == 'Benefit' ? 'badge-success' : 'badge-danger' }} badge-pill">
                                                        {{ $subCriteria->criteria->attribute_type }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <strong class="text-dark">Bobot Kriteria:</strong>
                                                <div class="mt-1">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 mr-3">
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-warning" 
                                                                     role="progressbar" 
                                                                     style="width: {{ $subCriteria->criteria->weight * 100 }}%"
                                                                     aria-valuenow="{{ $subCriteria->criteria->weight * 100 }}" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-warning font-weight-bold">
                                                            {{ number_format($subCriteria->criteria->weight, 2) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <strong class="text-dark">Keterangan:</strong>
                                                <div class="mt-1 text-muted small">
                                                    @if($subCriteria->criteria->attribute_type == 'Benefit')
                                                        <i class="fas fa-arrow-up text-success mr-1"></i>Nilai lebih besar lebih baik
                                                    @else
                                                        <i class="fas fa-arrow-down text-danger mr-1"></i>Nilai lebih kecil lebih baik
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Information -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-dark mb-3 border-bottom pb-2">
                                        <i class="fas fa-info-circle mr-2"></i>Informasi Detail Sub Kriteria
                                    </h6>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold text-dark" style="width: 200px;">
                                                        <i class="fas fa-tag text-primary mr-2"></i>Nama Sub Kriteria
                                                    </td>
                                                    <td class="text-dark">{{ $subCriteria->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-hashtag text-success mr-2"></i>Nilai
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success badge-pill" style="font-size: 1.1em;">
                                                            {{ $subCriteria->value }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-file-alt text-warning mr-2"></i>Deskripsi
                                                    </td>
                                                    <td class="text-muted">
                                                        @if($subCriteria->description)
                                                            {{ $subCriteria->description }}
                                                        @else
                                                            <span class="text-muted font-italic">- Tidak ada deskripsi -</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-toggle-on text-info mr-2"></i>Status
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $subCriteria->is_active ? 'badge-success' : 'badge-secondary' }} badge-pill">
                                                            <i class="fas {{ $subCriteria->is_active ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                                            {{ $subCriteria->is_active ? 'Aktif' : 'Nonaktif' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-calendar-plus text-info mr-2"></i>Dibuat Pada
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $subCriteria->created_at->format('d F Y H:i') }}
                                                        <small class="text-muted">({{ $subCriteria->created_at->diffForHumans() }})</small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-calendar-check text-warning mr-2"></i>Diperbarui Pada
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $subCriteria->updated_at->format('d F Y H:i') }}
                                                        <small class="text-muted">({{ $subCriteria->updated_at->diffForHumans() }})</small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.subcriteria.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <div>
                                    @if (auth()->user()->is_admin == 1)
                                    <a href="{{ route('admin.subcriteria.edit', $subCriteria->id) }}" class="btn btn-primary btn-lg mr-2">
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
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.border-left-primary {
    border-left: 4px solid #047857 !important;
}

.border-left-success {
    border-left: 4px solid #059669 !important;
}

.border-left-warning {
    border-left: 4px solid #f59e0b !important;
}

.btn-lg {
    border-radius: 8px;
    padding: 0.75rem 2rem;
}

.table-borderless td {
    border: none !important;
    padding: 0.75rem 0.5rem;
}

.border-right {
    border-right: 1px solid #e3e6f0 !important;
}

.badge-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.badge-pill {
    padding: 0.5em 0.8em;
    border-radius: 50rem;
}

.icon-container {
    transition: transform 0.3s ease;
}

.icon-container:hover {
    transform: scale(1.05);
}

.progress {
    border-radius: 5px;
}

.info-item {
    margin-bottom: 1rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
}
</style>
@endsection