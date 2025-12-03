@extends('layouts.app')

@section('title', 'Tambah Sub Kriteria')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-layer-group text-primary mr-2"></i>Sub Kriteria
        </h1>
        <a href="{{ route('subcriteria.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Sub Kriteria Baru
                    </h5>
                </div>
                <div class="card-body p-4">
                    <x-alert />

                    <form action="{{ route('subcriteria.store') }}" method="POST" id="subcriteriaForm">
                        @csrf
                        <input type="hidden" name="criteria_id" value="{{ $criteria->id }}">

                        <!-- Kriteria Info -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-left-info bg-light">
                                    <div class="card-body py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="font-weight-bold text-info mb-1">
                                                    <i class="fas fa-list-alt mr-2"></i>Informasi Kriteria Induk
                                                </h6>
                                                <div class="text-dark">
                                                    <strong>Kode:</strong> 
                                                    <span class="badge badge-primary">{{ $criteria->code }}</span>
                                                    <strong class="ml-3">Nama:</strong> 
                                                    <span class="text-dark">{{ $criteria->name }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <div class="text-info">
                                                    <strong>Bobot:</strong> 
                                                    <span class="font-weight-bold">{{ $criteria->weight }}</span>
                                                </div>
                                                <div>
                                                    <strong>Jenis:</strong> 
                                                    @if($criteria->attribute_type == 'Benefit')
                                                        <span class="badge badge-success">Benefit</span>
                                                    @else
                                                        <span class="badge badge-danger">Cost</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Input -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-tag text-primary mr-2"></i>Nama Sub Kriteria <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           name="name" 
                                           id="name"
                                           value="{{ old('name') }}" 
                                           placeholder="Masukkan nama sub kriteria"
                                           required
                                           autofocus
                                           maxlength="100">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Nama lengkap untuk sub kriteria ini
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="value" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-sort-numeric-up text-success mr-2"></i>Nilai Sub Kriteria <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('value') is-invalid @enderror" 
                                           name="value" 
                                           id="value"
                                           value="{{ old('value') }}" 
                                           placeholder="1"
                                           min="1" 
                                           max="10"
                                           step="1"
                                           required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Nilai numerik untuk sub kriteria (1-10)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-left-success">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 font-weight-bold text-success">
                                            <i class="fas fa-eye mr-2"></i>Preview Sub Kriteria
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Nama Sub Kriteria:</strong>
                                                <div id="namePreview" class="text-primary font-weight-bold mt-1">
                                                    {{ old('name') ?: '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Nilai:</strong>
                                                <div id="valuePreview" class="text-success font-weight-bold mt-1">
                                                    {{ old('value') ?: '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Sub Criteria -->
                        @if($criteria->subCriterias && $criteria->subCriterias->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-left-warning">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 font-weight-bold text-warning">
                                            <i class="fas fa-list mr-2"></i>Sub Kriteria yang Sudah Ada
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th width="5%">No</th>
                                                        <th width="40%">Nama Sub Kriteria</th>
                                                        <th width="15%">Nilai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($criteria->subCriterias as $index => $subCriteria)
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td>{{ $subCriteria->name }}</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-success">{{ $subCriteria->value }}</span>
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
                        @endif

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('subcriteria.index') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                    </a>
                                    <div>
                                        <button type="reset" class="btn btn-outline-secondary btn-lg mr-2">
                                            <i class="fas fa-undo mr-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-lg px-5">
                                            <i class="fas fa-save mr-2"></i>Simpan
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

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
}

.badge-primary {
    background-color: #059669;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const valueInput = document.getElementById('value');
    const descriptionInput = document.getElementById('description');
    
    const namePreview = document.getElementById('namePreview');
    const valuePreview = document.getElementById('valuePreview');
    const descriptionPreview = document.getElementById('descriptionPreview');

    // Real-time preview for name
    nameInput.addEventListener('input', function() {
        namePreview.textContent = this.value || '-';
    });

    // Real-time preview for value
    valueInput.addEventListener('input', function() {
        valuePreview.textContent = this.value || '-';
    });

    // Real-time preview for description
    descriptionInput.addEventListener('input', function() {
        descriptionPreview.textContent = this.value || '-';
    });

    // Form validation
    const form = document.getElementById('subcriteriaForm');
    form.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        const value = valueInput.value;
        
        let isValid = true;
        let errorMessage = '';

        if (!name) {
            isValid = false;
            errorMessage = 'Nama sub kriteria harus diisi!';
            nameInput.focus();
        } else if (!value || value < 1 || value > 100) {
            isValid = false;
            errorMessage = 'Nilai harus antara 1 - 100!';
            valueInput.focus();
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

    // Auto-focus on name input
    nameInput.focus();

    // Initialize previews
    if (nameInput.value) nameInput.dispatchEvent(new Event('input'));
    if (valueInput.value) valueInput.dispatchEvent(new Event('input'));
    if (descriptionInput.value) descriptionInput.dispatchEvent(new Event('input'));
});
</script>
@endsection