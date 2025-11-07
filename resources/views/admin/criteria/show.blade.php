@extends('layouts.app')

@section('title', 'Detail Kriteria')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-list-alt text-primary mr-2"></i>Kriteria
        </h1>
        <a href="{{ route('admin.criteria.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-eye mr-2"></i>Detail Kriteria
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Header Info -->
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <div class="criteria-header">
                                <div class="icon-container bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-list-alt fa-2x text-white"></i>
                                </div>
                                <h3 class="font-weight-bold text-primary mb-2">{{ $criteria->name }}</h3>
                                <div class="status-badges">
                                    <span class="badge badge-primary badge-pill mr-2">
                                        <i class="fas fa-code mr-1"></i>{{ $criteria->code }}
                                    </span>
                                    <span class="badge {{ $criteria->attribute_type == 'Benefit' ? 'badge-success' : 'badge-danger' }} badge-pill">
                                        <i class="fas fa-chart-bar mr-1"></i>{{ $criteria->attribute_type }}
                                    </span>
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
                                        <i class="fas fa-info-circle mr-2"></i>Informasi Detail
                                    </h6>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold text-dark" style="width: 200px;">
                                                        <i class="fas fa-code text-primary mr-2"></i>Kode Kriteria
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary badge-pill">
                                                            {{ $criteria->code }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-tag text-success mr-2"></i>Nama Kriteria
                                                    </td>
                                                    <td class="text-dark">{{ $criteria->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-weight text-warning mr-2"></i>Bobot Kriteria
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 mr-3">
                                                                <div class="progress" style="height: 10px;">
                                                                    <div class="progress-bar bg-warning" 
                                                                         role="progressbar" 
                                                                         style="width: {{ $criteria->weight * 100 }}%"
                                                                         aria-valuenow="{{ $criteria->weight * 100 }}" 
                                                                         aria-valuemin="0" 
                                                                         aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-warning font-weight-bold">
                                                                {{ number_format($criteria->weight, 2) }} ({{ round($criteria->weight * 100) }}%)
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-chart-bar text-info mr-2"></i>Jenis Atribut
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $criteria->attribute_type == 'Benefit' ? 'badge-success' : 'badge-danger' }} badge-pill">
                                                            <i class="fas {{ $criteria->attribute_type == 'Benefit' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                                            {{ $criteria->attribute_type }}
                                                        </span>
                                                        <small class="text-muted ml-2">
                                                            @if($criteria->attribute_type == 'Benefit')
                                                                Nilai lebih besar lebih baik
                                                            @else
                                                                Nilai lebih kecil lebih baik
                                                            @endif
                                                        </small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-calendar-plus text-info mr-2"></i>Dibuat Pada
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $criteria->created_at->format('d F Y H:i') }}
                                                        <small class="text-muted">({{ $criteria->created_at->diffForHumans() }})</small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-calendar-check text-warning mr-2"></i>Diperbarui Pada
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $criteria->updated_at->format('d F Y H:i') }}
                                                        <small class="text-muted">({{ $criteria->updated_at->diffForHumans() }})</small>
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
                                <a href="{{ route('admin.criteria.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <div>
                                    @if (auth()->user()->is_admin == 1)
                                    <a href="{{ route('admin.criteria.edit', $criteria->id) }}" class="btn btn-primary btn-lg mr-2">
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

.border-left-success {
    border-left: 4px solid #059669 !important;
}

.border-left-warning {
    border-left: 4px solid #f59e0b !important;
}

.border-left-primary {
    border-left: 4px solid #047857 !important;
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

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
}
</style>
@endsection