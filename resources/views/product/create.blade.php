@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-box text-primary mr-2"></i>Produk
        </h1>
        <a href="{{ route('product.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Produk
                    </h5>
                </div>
                <div class="card-body p-4">
                    <x-alert />

                    <form action="{{ route('product.store') }}" method="POST" id="productForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="code" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-hashtag text-info mr-2"></i>Kode Produk
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('code') is-invalid @enderror"
                                           name="code"
                                           id="code"
                                           value="{{ old('code') }}"
                                           placeholder="Masukkan kode produk"
                                           required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Contoh: P001
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-tag text-primary mr-2"></i>Nama Produk
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           name="name" 
                                           id="name"
                                           value="{{ old('name') }}" 
                                           placeholder="Masukkan nama produk"
                                           required
                                           autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Contoh: Kabel NYY 4x16 mm², Lampu LED Panel 60x60, dll.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-file-alt text-success mr-2"></i>Deskripsi Produk
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" 
                                              id="description"
                                              rows="4" 
                                              placeholder="Masukkan deskripsi lengkap tentang produk ini">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="form-text text-muted">
                                            <i class="fas fa-lightbulb mr-1"></i>Jelaskan spesifikasi dan kegunaan produk
                                        </small>
                                        <small class="text-muted" id="charCount">0/500 karakter</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-truck text-warning mr-2"></i>Distributor yang Menyediakan
                                    </label>
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            @if($distributors->count() > 0)
                                                <div class="row">
                                                    @foreach($distributors as $distributor)
                                                        <div class="col-md-6 mb-2">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" 
                                                                       class="custom-control-input" 
                                                                       name="distributors[]" 
                                                                       id="distributor_{{ $distributor->id }}" 
                                                                       value="{{ $distributor->id }}"
                                                                       {{ in_array($distributor->id, old('distributors', [])) ? 'checked' : '' }}>
                                                                <label class="custom-control-label font-weight-normal" for="distributor_{{ $distributor->id }}">
                                                                    {{ $distributor->name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center text-muted py-3">
                                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                                    <p class="mb-0">Belum ada data distributor. 
                                                        <a href="{{ route('distributor.create') }}" class="text-primary">Tambah distributor terlebih dahulu</a>
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @error('distributors')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Pilih distributor yang menyediakan produk ini
                                    </small>
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
                                            <div class="col-md-4">
                                                <strong>Kode Produk:</strong>
                                                <div id="codePreview" class="text-info font-weight-bold mt-1">
                                                    {{ old('code') ?: '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Nama Produk:</strong>
                                                <div id="namePreview" class="text-primary font-weight-bold mt-1">
                                                    {{ old('name') ?: '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Deskripsi:</strong>
                                                <div id="descriptionPreview" class="text-muted mt-1 small">
                                                    {{ old('description') ?: '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <strong>Distributor Terpilih:</strong>
                                                <div id="distributorsPreview" class="mt-1">
                                                    @if(old('distributors'))
                                                        @foreach($distributors->whereIn('id', old('distributors')) as $distributor)
                                                            <span class="badge badge-success mr-1 mb-1">{{ $distributor->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
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
                                    <a href="{{ route('product.index') }}" class="btn btn-secondary btn-lg">
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

.custom-checkbox .custom-control-label::before {
    border-radius: 4px;
}

.custom-checkbox .custom-control-input:checked~.custom-control-label::before {
    background-color: #059669;
    border-color: #059669;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const codePreview = document.getElementById('codePreview');
    const namePreview = document.getElementById('namePreview');
    const descriptionPreview = document.getElementById('descriptionPreview');
    const distributorsPreview = document.getElementById('distributorsPreview');
    const charCount = document.getElementById('charCount');
    const distributorCheckboxes = document.querySelectorAll('input[name="distributors[]"]');

    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
        codePreview.textContent = this.value || '-';
    });

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

    distributorCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateDistributorsPreview);
    });

    function updateDistributorsPreview() {
        const selectedDistributors = Array.from(distributorCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => {
                const label = document.querySelector(`label[for="${cb.id}"]`);
                return label ? label.textContent.trim() : '';
            })
            .filter(name => name !== '');

        distributorsPreview.textContent = '';

        if (selectedDistributors.length > 0) {
            selectedDistributors.forEach(name => {
                const badge = document.createElement('span');
                badge.className = 'badge badge-success mr-1 mb-1';
                badge.textContent = name;
                distributorsPreview.appendChild(badge);
            });
            return;
        }

        const emptyText = document.createElement('span');
        emptyText.className = 'text-muted';
        emptyText.textContent = '-';
        distributorsPreview.appendChild(emptyText);
    }

    const form = document.getElementById('productForm');
    form.addEventListener('submit', function(e) {
        const code = codeInput.value.trim();
        const name = nameInput.value.trim();

        if (!code || !name) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Data Produk Belum Lengkap',
                text: 'Harap masukkan kode dan nama produk.',
                confirmButtonColor: '#059669'
            });
            if (!code) {
                codeInput.focus();
            } else {
                nameInput.focus();
            }
        }
    });

    if (codeInput.value) {
        codeInput.dispatchEvent(new Event('input'));
    }
    if (descriptionInput.value) {
        descriptionInput.dispatchEvent(new Event('input'));
    }
    updateDistributorsPreview();
});
</script>
@endsection
