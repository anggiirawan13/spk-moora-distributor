@extends('layouts.app')

@section('title', 'Tambah Kriteria')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-list-alt text-primary mr-2"></i>Kriteria
        </h1>
        <a href="{{ route('criteria.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Data Kriteria
                    </h5>
                </div>
                <div class="card-body p-4">
                    <x-alert />

                    <form action="{{ route('criteria.store') }}" method="POST" id="criteriaForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-code text-primary mr-2"></i>Kode Kriteria <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           name="code" 
                                           id="code"
                                           value="{{ old('code') }}" 
                                           placeholder="Contoh: C1, C2, C3"
                                           required
                                           autofocus
                                           maxlength="10">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Kode unik untuk identifikasi kriteria
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-tag text-success mr-2"></i>Nama Kriteria <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           name="name" 
                                           id="name"
                                           value="{{ old('name') }}" 
                                           placeholder="Masukkan nama kriteria"
                                           required
                                           maxlength="100">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Nama lengkap kriteria penilaian
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="weight" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-weight text-warning mr-2"></i>Bobot Kriteria <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('weight') is-invalid @enderror" 
                                           name="weight" 
                                           id="weight"
                                           value="{{ old('weight') }}" 
                                           placeholder="0.00"
                                           min="0.01" 
                                           max="1" 
                                           step="0.01"
                                           required>
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Bobot antara 0.01 - 1.00 (Total semua bobot harus 1)
                                    </small>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div id="weightProgress" class="progress-bar bg-warning" role="progressbar" 
                                             style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted" id="weightPercentage">0%</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="attribute_type" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-chart-bar text-info mr-2"></i>Jenis Atribut <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('attribute_type') is-invalid @enderror" 
                                            name="attribute_type" 
                                            id="attribute_type" 
                                            required>
                                        <option value="" hidden>Pilih jenis atribut</option>
                                        <option value="Benefit" {{ old('attribute_type') == 'Benefit' ? 'selected' : '' }}>Benefit</option>
                                        <option value="Cost" {{ old('attribute_type') == 'Cost' ? 'selected' : '' }}>Cost</option>
                                    </select>
                                    @error('attribute_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <span id="attributeInfo">
                                            Benefit: Nilai lebih besar lebih baik | Cost: Nilai lebih kecil lebih baik
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-left-info">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 font-weight-bold text-info">
                                            <i class="fas fa-eye mr-2"></i>Preview Kriteria
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Kode:</strong>
                                                <div id="codePreview" class="text-primary font-weight-bold mt-1">
                                                    {{ old('code') ?: '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Nama:</strong>
                                                <div id="namePreview" class="text-dark mt-1">
                                                    {{ old('name') ?: '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <strong>Bobot:</strong>
                                                <div id="weightPreview" class="text-warning font-weight-bold mt-1">
                                                    {{ old('weight') ? old('weight') . ' (' . (old('weight') * 100) . '%)' : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Jenis Atribut:</strong>
                                                <div id="attributePreview" class="mt-1">
                                                    @if(old('attribute_type') == 'Benefit')
                                                        <span class="badge badge-success">Benefit</span>
                                                    @elseif(old('attribute_type') == 'Cost')
                                                        <span class="badge badge-danger">Cost</span>
                                                    @else
                                                        -
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
                                    <a href="{{ route('criteria.index') }}" class="btn btn-secondary btn-lg">
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

.badge-success {
    background-color: #059669;
}

.badge-danger {
    background-color: #dc2626;
}

.progress {
    border-radius: 4px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const nameInput = document.getElementById('name');
    const weightInput = document.getElementById('weight');
    const attributeSelect = document.getElementById('attribute_type');
    
    const codePreview = document.getElementById('codePreview');
    const namePreview = document.getElementById('namePreview');
    const weightPreview = document.getElementById('weightPreview');
    const attributePreview = document.getElementById('attributePreview');
    const weightProgress = document.getElementById('weightProgress');
    const weightPercentage = document.getElementById('weightPercentage');
    const attributeInfo = document.getElementById('attributeInfo');

    codeInput.addEventListener('input', function() {
        codePreview.textContent = this.value || '-';
    });

    nameInput.addEventListener('input', function() {
        namePreview.textContent = this.value || '-';
    });

    weightInput.addEventListener('input', function() {
        const weight = parseFloat(this.value) || 0;
        const percentage = Math.min(weight * 100, 100);
        
        weightProgress.style.width = percentage + '%';
        weightPercentage.textContent = percentage.toFixed(0) + '%';
        
        if (weight > 0) {
            weightPreview.textContent = weight.toFixed(2) + ' (' + percentage.toFixed(0) + '%)';
            
            if (weight > 0.3) {
                weightProgress.className = 'progress-bar bg-danger';
            } else if (weight > 0.15) {
                weightProgress.className = 'progress-bar bg-warning';
            } else {
                weightProgress.className = 'progress-bar bg-success';
            }
        } else {
            weightPreview.textContent = '-';
            weightProgress.style.width = '0%';
        }
    });

    attributeSelect.addEventListener('change', function() {
        const value = this.value;
        if (value === 'Benefit') {
            attributePreview.innerHTML = '<span class="badge badge-success">Benefit</span>';
            attributeInfo.innerHTML = 'Benefit: Nilai lebih besar lebih baik';
        } else if (value === 'Cost') {
            attributePreview.innerHTML = '<span class="badge badge-danger">Cost</span>';
            attributeInfo.innerHTML = 'Cost: Nilai lebih kecil lebih baik';
        } else {
            attributePreview.innerHTML = '-';
            attributeInfo.innerHTML = 'Benefit: Nilai lebih besar lebih baik | Cost: Nilai lebih kecil lebih baik';
        }
    });

    const form = document.getElementById('criteriaForm');
    form.addEventListener('submit', function(e) {
        const code = codeInput.value.trim();
        const name = nameInput.value.trim();
        const weight = parseFloat(weightInput.value);
        const attribute = attributeSelect.value;
        
        let isValid = true;
        let errorMessage = '';

        if (!code) {
            isValid = false;
            errorMessage = 'Kode kriteria harus diisi!';
            codeInput.focus();
        } else if (!name) {
            isValid = false;
            errorMessage = 'Nama kriteria harus diisi!';
            nameInput.focus();
        } else if (!weight || weight < 0.01 || weight > 1) {
            isValid = false;
            errorMessage = 'Bobot harus antara 0.01 - 1.00!';
            weightInput.focus();
        } else if (!attribute) {
            isValid = false;
            errorMessage = 'Jenis atribut harus dipilih!';
            attributeSelect.focus();
        }

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: errorMessage,
                confirmButtonColor: '#059669'
            });
        }
    });

    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    if (codeInput.value) codeInput.dispatchEvent(new Event('input'));
    if (nameInput.value) nameInput.dispatchEvent(new Event('input'));
    if (weightInput.value) weightInput.dispatchEvent(new Event('input'));
    if (attributeSelect.value) attributeSelect.dispatchEvent(new Event('change'));
});
</script>
@endsection