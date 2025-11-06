<!-- resources/views/admin/distributor/compare_form.blade.php -->
@extends('layouts.app')

@section('title', 'Pilih Distributor untuk Dibandingkan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-balance-scale text-primary mr-2"></i>Perbandingan Distributor
        </h1>
        <a href="{{ route('distributor.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="text-center">
                        <i class="fas fa-exchange-alt fa-3x mb-3"></i>
                        <h4 class="font-weight-bold mb-2">Pilih Dua Distributor untuk Dibandingkan</h4>
                        <p class="mb-0 opacity-8">Bandinkan fitur dan spesifikasi dari dua distributor berbeda</p>
                    </div>
                </div>
                
                <div class="card-body p-5">
                    <x-alert />

                    <form action="{{ route('distributor.compare') }}" method="POST" id="compareForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="distributor1" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-warehouse text-primary mr-2"></i>Distributor Pertama
                                    </label>
                                    <select name="distributor1" id="distributor1" class="form-control select2" required 
                                            data-placeholder="Pilih distributor pertama">
                                        <option value=""></option>
                                        @foreach ($distributors as $distributor)
                                            <option value="{{ $distributor->id }}" 
                                                    data-company="{{ $distributor->company_name }}"
                                                    data-category="{{ $distributor->product?->name ?? 'N/A' }}"
                                                    data-scale="{{ $distributor->businessScale?->name ?? 'N/A' }}">
                                                {{ $distributor->name }} - {{ $distributor->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="distributor1-preview" class="mt-2 p-3 bg-light rounded" style="display: none;">
                                        <small class="text-muted">Preview akan muncul di sini</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="distributor2" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-warehouse text-info mr-2"></i>Distributor Kedua
                                    </label>
                                    <select name="distributor2" id="distributor2" class="form-control select2" required
                                            data-placeholder="Pilih distributor kedua">
                                        <option value=""></option>
                                        @foreach ($distributors as $distributor)
                                            <option value="{{ $distributor->id }}"
                                                    data-company="{{ $distributor->company_name }}"
                                                    data-category="{{ $distributor->product?->name ?? 'N/A' }}"
                                                    data-scale="{{ $distributor->businessScale?->name ?? 'N/A' }}">
                                                {{ $distributor->name }} - {{ $distributor->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="distributor2-preview" class="mt-2 p-3 bg-light rounded" style="display: none;">
                                        <small class="text-muted">Preview akan muncul di sini</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comparison Preview -->
                        <div id="comparisonPreview" class="mt-4 p-4 border rounded" style="display: none;">
                            <h6 class="font-weight-bold text-center mb-3 text-primary">
                                <i class="fas fa-eye mr-2"></i>Preview Perbandingan
                            </h6>
                            <div class="row text-center">
                                <div class="col-md-5">
                                    <div id="preview1" class="border-right pr-3">
                                        <h6 class="font-weight-bold text-primary" id="preview1-name">-</h6>
                                        <small class="text-muted" id="preview1-details">-</small>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-exchange-alt fa-2x text-muted"></i>
                                </div>
                                <div class="col-md-5">
                                    <div id="preview2" class="border-left pl-3">
                                        <h6 class="font-weight-bold text-info" id="preview2-name">-</h6>
                                        <small class="text-muted" id="preview2-details">-</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="compareBtn" disabled>
                                <i class="fas fa-exchange-alt mr-2"></i>Bandingkan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Distributor</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $distributors->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Produk</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $distributors->groupBy('product_id')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Skala Bisnis</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $distributors->groupBy('business_scale_id')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
.bg-gradient-primary {
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.select2-container--default .select2-selection--single {
    border: 1px solid #d1d3e2;
    border-radius: 8px;
    padding: 0.375rem 0.75rem;
    height: calc(2.25rem + 2px);
}

.select2-container--default .select2-selection--single:focus {
    border-color: #059669;
    box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
}

.border-left-primary { border-left-color: #059669 !important; }
.border-left-success { border-left-color: #10b981 !important; }
.border-left-info { border-left-color: #3b82f6 !important; }

.btn-lg {
    padding: 0.75rem 2rem;
    font-size: 1.1rem;
    border-radius: 8px;
}
</style>

<script>
// Fungsi untuk menunggu sampai Select2 tersedia
function waitForSelect2(callback) {
    if (typeof $.fn.select2 !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForSelect2(callback);
        }, 100);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    waitForSelect2(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: function() {
                return $(this).data('placeholder');
            },
            width: '100%'
        });

        // Preview functionality
        function updatePreview(selectId, previewId, nameId, detailsId) {
            const select = $(selectId);
            const selectedOption = select.find('option:selected');
            const preview = $(previewId);
            const nameElement = $(nameId);
            const detailsElement = $(detailsId);

            if (selectedOption.val()) {
                const company = selectedOption.data('company');
                const category = selectedOption.data('category');
                const scale = selectedOption.data('scale');
                
                nameElement.text(selectedOption.text());
                detailsElement.text(`${company} • ${category} • ${scale}`);
                preview.show();
            } else {
                preview.hide();
            }
            
            updateComparisonPreview();
            updateSubmitButton();
        }

        function updateComparisonPreview() {
            const distributor1 = $('#distributor1').val();
            const distributor2 = $('#distributor2').val();
            
            if (distributor1 && distributor2) {
                $('#comparisonPreview').slideDown();
            } else {
                $('#comparisonPreview').slideUp();
            }
        }

        function updateSubmitButton() {
            const distributor1 = $('#distributor1').val();
            const distributor2 = $('#distributor2').val();
            
            if (distributor1 && distributor2 && distributor1 !== distributor2) {
                $('#compareBtn').prop('disabled', false);
            } else {
                $('#compareBtn').prop('disabled', true);
            }
        }

        // Event listeners
        $('#distributor1').on('change', function() {
            updatePreview('#distributor1', '#distributor1-preview', '#preview1-name', '#preview1-details');
        });

        $('#distributor2').on('change', function() {
            updatePreview('#distributor2', '#distributor2-preview', '#preview2-name', '#preview2-details');
        });

        // Form validation
        $('#compareForm').on('submit', function(e) {
            const distributor1 = $('#distributor1').val();
            const distributor2 = $('#distributor2').val();
            
            if (distributor1 === distributor2) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Harap pilih dua distributor yang berbeda untuk dibandingkan.',
                    confirmButtonColor: '#059669'
                });
            }
        });
    });
});
</script>
@endsection