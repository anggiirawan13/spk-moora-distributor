@extends('layouts.app')

@section('title', 'Tambah Termin Pembayaran')

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
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Termin Pembayaran Baru
                    </h5>
                </div>
                <div class="card-body p-4">
                    <x-alert />

                    <form action="{{ route('payment_term.store') }}" method="POST" id="paymentTermForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-tag text-primary mr-2"></i>Nama
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           name="name" 
                                           id="name"
                                           value="{{ old('name') }}" 
                                           placeholder="Masukkan nama termin pembayaran"
                                           required
                                           autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Contoh: Cash, 0 Day, 3 Day, 30 Day
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
                                              placeholder="Masukkan deskripsi lengkap tentang termin pembayaran ini">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="form-text text-muted">
                                            <i class="fas fa-lightbulb mr-1"></i>Jelaskan tentang termin pembayaran
                                        </small>
                                        <small class="text-muted" id="charCount">0/500 karakter</small>
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
                                                    {{ old('name') ?: '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Deskripsi:</strong>
                                                <div id="descriptionPreview" class="text-muted mt-1 small">
                                                    {{ old('description') ?: '-' }}
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
                                    <a href="{{ route('payment_term.index') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save mr-2"></i>Simpan
                                    </button>
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
.bg-gradient-primary {
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
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

    const form = document.getElementById('paymentTermForm');
    form.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        
        if (!name) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Nama Kosong',
                text: 'Harap masukkan nama termin pembayaran.',
                confirmButtonColor: '#059669'
            });
            nameInput.focus();
        }
    });

    if (descriptionInput.value) {
        descriptionInput.dispatchEvent(new Event('input'));
    }
});
</script>
@endsection