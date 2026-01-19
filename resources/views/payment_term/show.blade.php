@extends('layouts.app')

@section('title', 'Detail Termin Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-money-bill-wave text-primary mr-2"></i>Termin Pembayaran
        </h1>
        <a href="{{ route('payment_term.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-eye mr-2"></i>Detail Termin Pembayaran
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="text-center mb-4">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-chart-line fa-2x text-white"></i>
                                </div>
                                <h4 class="font-weight-bold text-primary">{{ $paymentTerm->name }}</h4>
                                @if($paymentTerm->description)
                                <p class="text-muted mb-0">{{ $paymentTerm->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

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
                                                        <i class="fas fa-tag text-primary mr-2"></i>Nama Termin Pembayaran
                                                    </td>
                                                    <td class="text-dark">{{ $paymentTerm->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-file-alt text-success mr-2"></i>Deskripsi
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $paymentTerm->description ?: '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-calendar-plus text-info mr-2"></i>Dibuat Pada
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $paymentTerm->created_at->format('d F Y H:i') }}
                                                        <small class="text-muted">({{ $paymentTerm->created_at->diffForHumans() }})</small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-calendar-check text-warning mr-2"></i>Diperbarui Pada
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $paymentTerm->updated_at->format('d F Y H:i') }}
                                                        <small class="text-muted">({{ $paymentTerm->updated_at->diffForHumans() }})</small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('payment_term.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <div>
                                    @if (auth()->user()->is_admin == 1)
                                    <a href="{{ route('payment_term.edit', $paymentTerm->id) }}" class="btn btn-primary btn-lg mr-2">
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

.border-left-success {
    border-left: 4px solid #059669 !important;
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
</style>
@endsection