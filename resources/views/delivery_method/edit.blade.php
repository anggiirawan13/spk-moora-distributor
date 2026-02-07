@extends('layouts.app')

@section('title', 'Edit Metode Pengiriman')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shipping-fast text-primary mr-2"></i>Metode Pengiriman
        </h1>
        <a href="{{ route('delivery_method.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-warning text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-edit mr-2"></i>Edit Metode Pengiriman
                    </h5>
                </div>
                <div class="card-body p-4">
                    <x-alert />

                    <form action="{{ route('delivery_method.update', $deliveryMethod->id) }}" method="POST" id="deliveryMethodForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="code" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-hashtag text-info mr-2"></i>Kode Metode Pengiriman <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('code') is-invalid @enderror"
                                           name="code"
                                           id="code"
                                           value="{{ old('code', $deliveryMethod->code) }}"
                                           placeholder="Masukkan kode metode pengiriman"
                                           required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Contoh: MP001
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-truck text-primary mr-2"></i>Nama Metode Pengiriman <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           name="name" 
                                           id="name"
                                           value="{{ old('name', $deliveryMethod->name) }}" 
                                           placeholder="Masukkan nama metode pengiriman"
                                           required
                                           autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Contoh: Pengiriman Ekspres, Reguler, COD
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-file-alt text-success mr-2"></i>Deskripsi
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" 
                                              id="description"
                                              rows="4" 
                                              placeholder="Masukkan deskripsi lengkap tentang metode pengiriman ini">{{ old('description', $deliveryMethod->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="form-text text-muted">
                                            <i class="fas fa-lightbulb mr-1"></i>Jelaskan estimasi waktu dan cara pengiriman
                                        </small>
                                        <small class="text-muted" id="charCount">{{ strlen(old('description', $deliveryMethod->description)) }}/500 karakter</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-left-info">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 font-weight-bold text-info">
                                            <i class="fas fa-eye mr-2"></i>Preview
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Nama:</strong>
                                                <div id="namePreview" class="text-primary font-weight-bold mt-1">
                                                    {{ old('name', $deliveryMethod->name) ?: '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Deskripsi:</strong>
                                                <div id="descriptionPreview" class="text-muted mt-1 small">
                                                    {{ old('description', $deliveryMethod->description) ?: '-' }}
                                                </div>
                                            </div>
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
                                            <i class="fas fa-history mr-2"></i>Data Sebelumnya
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Nama:</strong>
                                                <div class="text-dark mt-1">{{ $deliveryMethod->name }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Deskripsi:</strong>
                                                <div class="text-muted mt-1 small">{{ $deliveryMethod->description ?: '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('delivery_method.index') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                    </a>
                                    <div>
                                        <button type="reset" class="btn btn-outline-secondary btn-lg mr-2">
                                            <i class="fas fa-undo mr-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-lg px-5">
                                            <i class="fas fa-save mr-2"></i>Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #047857 0%, #0adf9b 100%) !important;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.form-control-lg {
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

.form-control {
    border-radius: 6px;
    border: 1px solid #d1d3e2;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #059669;
    box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-secondary {
    border-left: 4px solid #6c757d !important;
}

.btn-lg {
    border-radius: 8px;
    padding: 0.75rem 2rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const namePreview = document.getElementById('namePreview');
    const descriptionPreview = document.getElementById('descriptionPreview');
    const charCount = document.getElementById('charCount');

    nameInput.addEventListener('input', function() {
        namePreview.textContent = this.value || '-';
    });

    descriptionInput.addEventListener('input', function() {
        const text = this.value;
        descriptionPreview.textContent = text || '-';
        charCount.textContent = `${text.length}/500 karakter`;
        
        if (text.length > 450) {
            charCount.className = 'text-warning';
        } else if (text.length > 490) {
            charCount.className = 'text-danger';
        } else {
            charCount.className = 'text-muted';
        }
    });

    const form = document.getElementById('deliveryMethodForm');
    form.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        
        if (!name) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Nama Metode Pengiriman Kosong',
                text: 'Harap masukkan nama metode pengiriman.',
                confirmButtonColor: '#f59e0b'
            });
            nameInput.focus();
        }
    });

    if (nameInput.value) {
        nameInput.dispatchEvent(new Event('input'));
    }
    if (descriptionInput.value) {
        descriptionInput.dispatchEvent(new Event('input'));
    }
});
</script>
@endsection