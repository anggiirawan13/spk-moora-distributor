@extends('layouts.app')

@section('title', 'Detail Alternatif')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-warehouse text-primary mr-2"></i>Alternatif
        </h1>
        <a href="{{ route('alternative.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-eye mr-2"></i>Detail Alternatif
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <div class="alternative-header">
                                <div class="icon-container bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-car fa-2x text-white"></i>
                                </div>
                                <h3 class="font-weight-bold text-primary mb-2">{{ $alternative->distributor?->name }}</h3>
                                <div class="status-badges">
                                    <span class="badge badge-primary badge-pill mr-2">
                                        <i class="fas fa-id-card mr-1"></i>{{ $alternative->distributor?->npwp_formatted }}
                                    </span>
                                    @if($alternative->score)
                                    <span class="badge badge-success badge-pill">
                                        <i class="fas fa-chart-line mr-1"></i>Skor: {{ number_format($alternative->score, 3) }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-left-primary">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 font-weight-bold text-primary">
                                        <i class="fas fa-warehouse mr-2"></i>Informasi Distributor
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="info-item">
                                                <strong class="text-dark">Nama Distributor:</strong>
                                                <div class="text-primary font-weight-bold mt-1">{{ $alternative->distributor?->name }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-item">
                                                <strong class="text-dark">NPWP:</strong>
                                                <div class="text-dark mt-1">{{ $alternative->distributor?->npwp_formatted }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-item">
                                                <strong class="text-dark">Email:</strong>
                                                <div class="text-muted mt-1">{{ $alternative->distributor?->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-item">
                                                <strong class="text-dark">Telepon:</strong>
                                                <div class="text-muted mt-1">{{ $alternative->distributor?->phone ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-left-success">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 font-weight-bold text-success">
                                        <i class="fas fa-clipboard-check mr-2"></i>Nilai Kriteria
                                        <span class="badge badge-success ml-2">{{ $alternative->values->count() }}</span>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="10%" class="text-center">Kode</th>
                                                    <th width="25%">Kriteria</th>
                                                    <th width="20%" class="text-center">Bobot</th>
                                                    <th width="15%" class="text-center">Jenis</th>
                                                    <th width="30%">Sub-Kriteria Terpilih</th>
                                                    <th width="10%" class="text-center">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($alternative->values as $value)
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-primary">{{ $value->subCriteria->criteria->code ?? '-' }}</span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="font-weight-bold text-dark">{{ $value->subCriteria->criteria->name ?? '-' }}</div>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-warning badge-pill">
                                                            {{ $value->subCriteria->criteria->weight ?? '0' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @if($value->subCriteria->criteria->attribute_type == 'Benefit')
                                                            <span class="badge badge-success">Benefit</span>
                                                        @elseif($value->subCriteria->criteria->attribute_type == 'Cost')
                                                            <span class="badge badge-danger">Cost</span>
                                                        @else
                                                            <span class="badge badge-secondary">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="font-weight-bold text-dark">{{ $value->subCriteria->name ?? '-' }}</div>
                                                        @if($value->subCriteria->description)
                                                        <small class="text-muted">{{ $value->subCriteria->description }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-success badge-pill" style="font-size: 1.1em;">
                                                            {{ $value->subCriteria->value ?? '-' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($alternative->values->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-left-warning">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 font-weight-bold text-warning">
                                        <i class="fas fa-chart-bar mr-2"></i>Ringkasan Penilaian
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <div class="border-right">
                                                <h3 class="text-primary font-weight-bold">{{ $alternative->values->count() }}</h3>
                                                <p class="text-muted mb-0">Total Kriteria</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="border-right">
                                                <h3 class="text-success font-weight-bold">
                                                    {{ $alternative->values->sum(function($value) { return $value->subCriteria->value ?? 0; }) }}
                                                </h3>
                                                <p class="text-muted mb-0">Total Nilai</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div>
                                                <h3 class="text-info font-weight-bold">
                                                    @php
                                                        $values = $alternative->values->map(function($value) {
                                                            return $value->subCriteria->value ?? 0;
                                                        })->filter();
                                                        $average = $values->count() > 0 ? $values->avg() : 0;
                                                    @endphp
                                                    {{ number_format($average, 2) }}
                                                </h3>
                                                <p class="text-muted mb-0">Rata-rata Nilai</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-left-secondary">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 font-weight-bold text-secondary">
                                        <i class="fas fa-info-circle mr-2"></i>Informasi Sistem
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <strong class="text-dark">Dibuat Pada:</strong>
                                                <div class="text-muted mt-1">
                                                    {{ $alternative->created_at->format('d F Y H:i') }}
                                                    <small class="text-muted">({{ $alternative->created_at->diffForHumans() }})</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <strong class="text-dark">Diperbarui Pada:</strong>
                                                <div class="text-muted mt-1">
                                                    {{ $alternative->updated_at->format('d F Y H:i') }}
                                                    <small class="text-muted">({{ $alternative->updated_at->diffForHumans() }})</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('alternative.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <div>
                                    @if (auth()->user()->is_admin == 1)
                                    <a href="{{ route('alternative.edit', $alternative->id) }}" class="btn btn-primary btn-lg mr-2">
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

.border-left-secondary {
    border-left: 4px solid #6c757d !important;
}

.btn-lg {
    border-radius: 8px;
    padding: 0.75rem 2rem;
}

.badge-pill {
    padding: 0.5em 0.8em;
    border-radius: 50rem;
}

.badge-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.icon-container {
    transition: transform 0.3s ease;
}

.icon-container:hover {
    transform: scale(1.05);
}

.border-right {
    border-right: 1px solid #e3e6f0 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
}

.info-item {
    margin-bottom: 1rem;
}

.info-item:last-child {
    margin-bottom: 0;
}
</style>
@endsection
