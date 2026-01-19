@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users text-primary mr-2"></i>Pengguna
        </h1>
        <a href="{{ route('user.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-eye mr-2"></i>Detail Akun: {{ $user->name }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <div class="user-header">
                                <div class="avatar-container mb-3">
                                    @if($user->image_name)
                                        <img src="{{ asset('storage/user/' . $user->image_name) }}" 
                                             class="avatar-image rounded-circle shadow" 
                                             alt="{{ $user->name }}"
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                             style="width: 120px; height: 120px;">
                                            <i class="fas fa-user fa-3x text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="font-weight-bold text-primary mb-2">{{ $user->name }}</h3>
                                <div class="status-badges">
                                    <span class="badge badge-success badge-pill">
                                        <i class="fas fa-circle mr-1"></i>
                                        Aktif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-dark mb-3 border-bottom pb-2">
                                        <i class="fas fa-info-circle mr-2"></i>Informasi Akun
                                    </h6>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold text-dark" style="width: 200px;">
                                                        <i class="fas fa-user text-success mr-2"></i>Nama Lengkap
                                                    </td>
                                                    <td class="text-dark">{{ $user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-envelope text-info mr-2"></i>Alamat Email
                                                    </td>
                                                    <td class="text-dark">{{ $user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-user-tag text-warning mr-2"></i>Role Akun
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $user->is_admin ? 'badge-danger' : 'badge-primary' }} badge-pill">
                                                            <i class="fas {{ $user->is_admin ? 'fa-shield-alt' : 'fa-user' }} mr-1"></i>
                                                            {{ $user->is_admin ? 'Administrator' : 'Staf' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @if($user->phone)
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-phone text-primary mr-2"></i>Nomor Telepon
                                                    </td>
                                                    <td class="text-muted">{{ $user->phone }}</td>
                                                </tr>
                                                @endif
                                                @if($user->address)
                                                <tr>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-map-marker-alt text-danger mr-2"></i>Alamat
                                                    </td>
                                                    <td class="text-muted">{{ $user->address }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-left-secondary">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 font-weight-bold text-secondary">
                                        <i class="fas fa-cogs mr-2"></i>Informasi Sistem
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <strong class="text-dark">
                                                    <i class="fas fa-calendar-plus text-info mr-2"></i>Dibuat Pada:
                                                </strong>
                                                <div class="text-muted mt-1">
                                                    {{ $user->created_at->format('d F Y H:i') }}
                                                    <small class="text-muted">({{ $user->created_at->diffForHumans() }})</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <strong class="text-dark">
                                                    <i class="fas fa-calendar-check text-warning mr-2"></i>Diperbarui Pada:
                                                </strong>
                                                <div class="text-muted mt-1">
                                                    {{ $user->updated_at->format('d F Y H:i') }}
                                                    <small class="text-muted">({{ $user->updated_at->diffForHumans() }})</small>
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
                                <a href="{{ route('user.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <div>
                                    @if (auth()->user()->is_admin == 1)
                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-lg mr-2">
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
    background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%) !important;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.border-left-primary {
    border-left: 4px solid #047857 !important;
}

.border-left-secondary {
    border-left: 4px solid #6c757d !important;
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

.badge-pill {
    padding: 0.5em 0.8em;
    border-radius: 50rem;
}

.avatar-container {
    position: relative;
}

.avatar-image {
    border: 4px solid #e3e6f0;
    transition: all 0.3s ease;
}

.avatar-image:hover {
    border-color: #059669;
    transform: scale(1.05);
}

.info-item {
    margin-bottom: 1rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.user-header {
    padding: 1rem 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('User detail page loaded');
});
</script>
@endsection