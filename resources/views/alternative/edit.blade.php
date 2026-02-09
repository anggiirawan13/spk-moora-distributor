@extends('layouts.app')

@section('title', 'Edit Alternatif')

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
                <div class="card-header bg-gradient-warning text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-edit mr-2"></i>Edit Data Alternatif
                    </h5>
                </div>
                <div class="card-body p-4">
                    <x-alert />

                    <form action="{{ route('alternative.update', $alternative->id) }}" method="POST" id="alternativeForm">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-warehouse text-primary mr-2"></i>Distributor
                                </h6>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="distributor_id" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-truck-loading text-success mr-2"></i>Pilih Distributor <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('distributor_id') is-invalid @enderror" 
                                            name="distributor_id" 
                                            id="distributor_id" 
                                            required>
                                        <option value="" hidden>Pilih distributor</option>
                                        @foreach ($distributors as $distributor)
                                            <option value="{{ $distributor->id }}" 
                                                {{ old('distributor_id', $alternative->distributor_id) == $distributor->id ? 'selected' : '' }}>
                                                {{ $distributor->name }} - {{ $distributor->npwp_formatted }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('distributor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Pilih distributor yang akan dinilai
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4" id="distributorPreview" style="display: none;">
                            <div class="col-12">
                                <div class="card border-left-success">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 font-weight-bold text-success">
                                            <i class="fas fa-eye mr-2"></i>Preview Distributor Terpilih
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Nama Distributor:</strong>
                                                <div id="previewDistributorName" class="text-primary font-weight-bold mt-1">-</div>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>NPWP:</strong>
                                                <div id="previewNpwp" class="text-dark mt-1">-</div>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Produk:</strong>
                                                <div id="previewProduct" class="text-info mt-1">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-clipboard-check text-warning mr-2"></i>Penilaian Kriteria
                                </h6>
                                <p class="text-muted mb-4">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Berikan penilaian untuk setiap kriteria berdasarkan sub-kriteria yang tersedia
                                </p>
                            </div>
                            
                            @foreach ($criteria as $index => $k)
                            <div class="col-md-6 mb-4">
                                <div class="card criteria-card h-100">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 font-weight-bold text-dark">
                                            <span class="badge badge-primary mr-2">{{ $k->code }}</span>
                                            {{ $k->name }}
                                        </h6>
                                        <small class="text-muted">
                                            Bobot: <span class="font-weight-bold">{{ $k->weight }}</span> | 
                                            Jenis: 
                                            @if($k->attribute_type == 'Benefit')
                                                <span class="badge badge-success">Benefit</span>
                                            @else
                                                <span class="badge badge-danger">Cost</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-0">
                                            <label for="criteria_{{ $k->id }}" class="font-weight-bold text-dark mb-2 small">
                                                Pilih Sub-Kriteria:
                                            </label>
                                            <select class="form-control @error('criteria.' . $k->id) is-invalid @enderror" 
                                                    name="criteria[{{ $k->id }}]" 
                                                    id="criteria_{{ $k->id }}" 
                                                    required
                                                    data-criteria="{{ $k->code }}">
                                                <option value="" disabled>-- Pilih Sub-Kriteria --</option>
                                                @foreach ($k->subCriteria as $sub)
                                                    <option value="{{ $sub->id }}" 
                                                        {{ old('criteria.' . $k->id, $selectedSubs[$k->id] ?? '') == $sub->id ? 'selected' : '' }}>
                                                        {{ $sub->name }} (Nilai: {{ $sub->value }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('criteria.' . $k->id)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-left-info">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 font-weight-bold text-info">
                                            <i class="fas fa-list-alt mr-2"></i>Ringkasan Penilaian
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th width="10%">Kode</th>
                                                        <th width="30%">Kriteria</th>
                                                        <th width="25%">Sub-Kriteria Terpilih</th>
                                                        <th width="15%">Nilai</th>
                                                        <th width="10%">Bobot</th>
                                                        <th width="10%">Jenis</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="summaryTable">
                                                    @foreach ($criteria as $k)
                                                    <tr data-criteria="{{ $k->code }}">
                                                        <td class="text-center">
                                                            <span class="badge badge-primary">{{ $k->code }}</span>
                                                        </td>
                                                        <td>{{ $k->name }}</td>
                                                        <td id="summary_{{ $k->code }}_name" class="text-muted">
                                                            @php
                                                                $selectedSubId = $selectedSubs[$k->id] ?? null;
                                                                $selectedSub = $k->subCriteria->firstWhere('id', $selectedSubId);
                                                            @endphp
                                                            {{ $selectedSub ? $selectedSub->name : '-' }}
                                                        </td>
                                                        <td id="summary_{{ $k->code }}_value" class="text-center text-success font-weight-bold">
                                                            {{ $selectedSub ? $selectedSub->value : '-' }}
                                                        </td>
                                                        <td class="text-center">{{ $k->weight }}</td>
                                                        <td class="text-center">
                                                            @if($k->attribute_type == 'Benefit')
                                                                <span class="badge badge-success">B</span>
                                                            @else
                                                                <span class="badge badge-danger">C</span>
                                                            @endif
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
                                                <strong>Distributor:</strong>
                                                <div class="text-primary font-weight-bold mt-1">
                                                    {{ $alternative->distributor->name }} - {{ $alternative->distributor->npwp_formatted }}
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <strong>Sub-Kriteria Terpilih:</strong>
                                                <div class="mt-1">
                                                    @foreach ($criteria as $k)
                                                        @php
                                                            $selectedSubId = $selectedSubs[$k->id] ?? null;
                                                            $selectedSub = $k->subCriteria->firstWhere('id', $selectedSubId);
                                                        @endphp
                                                        @if($selectedSub)
                                                            <span class="badge badge-light border mr-2 mb-2">
                                                                <strong>{{ $k->code }}:</strong> {{ $selectedSub->name }} ({{ $selectedSub->value }})
                                                            </span>
                                                        @endif
                                                    @endforeach
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

.border-left-success {
    border-left: 4px solid #059669 !important;
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

.criteria-card {
    transition: all 0.3s ease;
    border: 1px solid #e3e6f0;
}

.criteria-card:hover {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
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
    const distributorSelect = document.getElementById('distributor_id');
    const distributorPreview = document.getElementById('distributorPreview');
    const previewDistributorName = document.getElementById('previewDistributorName');
    const previewNpwp = document.getElementById('previewNpwp');
    const previewProduct = document.getElementById('previewProduct');
    
    const distributorsData = @json($distributors->map(function($distributor) {
        return [
            'id' => $distributor->id,
            'name' => $distributor->name,
            'npwp' => $distributor->npwp_formatted,
            'product' => $distributor->product->name ?? '-'
        ];
    }));

    distributorSelect.addEventListener('change', function() {
        const selectedId = parseInt(this.value);
        const selectedDistributor = distributorsData.find(d => d.id === selectedId);
        
        if (selectedDistributor) {
            previewDistributorName.textContent = selectedDistributor.name;
            previewNpwp.textContent = selectedDistributor.npwp;
            previewProduct.textContent = selectedDistributor.product;
            distributorPreview.style.display = 'block';
        } else {
            distributorPreview.style.display = 'none';
        }
    });

    document.querySelectorAll('select[name^="criteria"]').forEach(select => {
        select.addEventListener('change', function() {
            const criteriaCode = this.getAttribute('data-criteria');
            const selectedOption = this.options[this.selectedIndex];
            const optionText = selectedOption.text;
            const subCriteriaName = optionText.split(' (Nilai: ')[0];
            const subCriteriaValue = optionText.match(/Nilai: (\d+)/)?.[1] || '-';
            
            document.getElementById(`summary_${criteriaCode}_name`).textContent = subCriteriaName;
            document.getElementById(`summary_${criteriaCode}_value`).textContent = subCriteriaValue;
            
            const safeCriteria = (window.CSS && CSS.escape) ? CSS.escape(criteriaCode) : criteriaCode.replace(/["\\]/g, '\\$&');
            const row = document.querySelector(`tr[data-criteria="${safeCriteria}"]`);
            if (row) {
                row.classList.add('table-success');
                setTimeout(() => row.classList.remove('table-success'), 1000);
            }
        });
    });

    const form = document.getElementById('alternativeForm');
    form.addEventListener('submit', function(e) {
        const distributorId = distributorSelect.value;
        const criteriaSelects = document.querySelectorAll('select[name^="criteria"]');
        let isValid = true;
        let errorMessage = '';

        if (!distributorId) {
            isValid = false;
            errorMessage = 'Harap pilih distributor terlebih dahulu!';
            distributorSelect.focus();
        } else {
            const unselectedCriteria = [];
            criteriaSelects.forEach(select => {
                if (!select.value) {
                    const criteriaCode = select.getAttribute('data-criteria');
                    unselectedCriteria.push(criteriaCode);
                }
            });

            if (unselectedCriteria.length > 0) {
                isValid = false;
                errorMessage = `Harap pilih sub-kriteria untuk: ${unselectedCriteria.join(', ')}`;
                if (criteriaSelects.length > 0) {
                    criteriaSelects[0].focus();
                }
            }
        }

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: errorMessage,
                confirmButtonColor: '#f59e0b'
            });
        }
    });

    if (distributorSelect.value) {
        distributorSelect.dispatchEvent(new Event('change'));
    }

    document.querySelectorAll('select[name^="criteria"]').forEach(select => {
        if (select.value) {
            select.dispatchEvent(new Event('change'));
        }
    });
});
</script>
@endsection
