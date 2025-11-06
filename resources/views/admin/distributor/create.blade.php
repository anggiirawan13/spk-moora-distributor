@extends('layouts.app')

@section('title', 'Tambah Distributor Barang Elektrikal')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-warehouse text-primary mr-2"></i>Distributor
        </h1>
        <a href="{{ route('distributor.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Data Distributor Baru
                    </h5>
                </div>
                <div class="card-body p-4">
                    <x-alert />

                    <form action="{{ route('distributor.store') }}" method="POST" enctype="multipart/form-data" id="distributorForm">
                        @csrf

                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-info-circle mr-2"></i>Informasi Dasar
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-tag text-primary mr-2"></i>Nama Distributor <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           name="name" 
                                           id="name"
                                           value="{{ old('name') }}" 
                                           placeholder="Masukkan nama distributor"
                                           required
                                           autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-building text-success mr-2"></i>Nama Perusahaan <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('company_name') is-invalid @enderror" 
                                           name="company_name" 
                                           id="company_name"
                                           value="{{ old('company_name') }}" 
                                           placeholder="Masukkan nama perusahaan"
                                           required>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Logo Perusahaan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image_name" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-image text-warning mr-2"></i>Logo Perusahaan
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               class="custom-file-input @error('image_name') is-invalid @enderror" 
                                               name="image_name" 
                                               id="image_name" 
                                               accept="image/*"
                                               onchange="previewImage(event)">
                                        <label class="custom-file-label" for="image_name" id="image_name_label">
                                            Pilih file logo...
                                        </label>
                                        @error('image_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Format: JPG, PNG, GIF (Maksimal 2MB)
                                    </small>
                                    
                                    <!-- Image Preview -->
                                    <div class="mt-3 text-center">
                                        <img id="imagePreview" class="img-thumbnail" 
                                             style="max-width: 200px; max-height: 200px; display: none;" 
                                             alt="Preview Logo Perusahaan" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kontak & Alamat -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-address-book mr-2"></i>Kontak & Alamat
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-envelope text-info mr-2"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email" 
                                           id="email"
                                           value="{{ old('email') }}" 
                                           placeholder="Masukkan alamat email"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-phone text-success mr-2"></i>Telepon <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" 
                                           id="phone"
                                           value="{{ old('phone') }}" 
                                           placeholder="Masukkan nomor telepon"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-map-marker-alt text-danger mr-2"></i>Alamat <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              name="address" 
                                              id="address"
                                              rows="3" 
                                              placeholder="Masukkan alamat lengkap"
                                              required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Klasifikasi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-tags mr-2"></i>Klasifikasi
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_term_id" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-money-bill-wave text-success mr-2"></i>Termin Pembayaran <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('payment_term_id') is-invalid @enderror" 
                                            name="payment_term_id" 
                                            id="payment_term_id" 
                                            required>
                                        <option value="" hidden>Pilih termin pembayaran</option>
                                        @foreach ($paymentTerms as $term)
                                            <option value="{{ $term->id }}" {{ old('payment_term_id') == $term->id ? 'selected' : '' }}>
                                                {{ $term->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_term_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery_method_id" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-shipping-fast text-info mr-2"></i>Metode Pengiriman <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('delivery_method_id') is-invalid @enderror" 
                                            name="delivery_method_id" 
                                            id="delivery_method_id" 
                                            required>
                                        <option value="" hidden>Pilih metode pengiriman</option>
                                        @foreach ($deliveryMethods as $method)
                                            <option value="{{ $method->id }}" {{ old('delivery_method_id') == $method->id ? 'selected' : '' }}>
                                                {{ $method->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('delivery_method_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_scale_id" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-chart-line text-primary mr-2"></i>Skala Bisnis <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('business_scale_id') is-invalid @enderror" 
                                            name="business_scale_id" 
                                            id="business_scale_id" 
                                            required>
                                        <option value="" hidden>Pilih skala bisnis</option>
                                        @foreach ($businessScales as $scale)
                                            <option value="{{ $scale->id }}" {{ old('business_scale_id') == $scale->id ? 'selected' : '' }}>
                                                {{ $scale->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('business_scale_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi & Status -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-file-alt mr-2"></i>Informasi Tambahan
                                </h6>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="description" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-file-alt text-secondary mr-2"></i>Deskripsi Perusahaan
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" 
                                              id="description"
                                              rows="4" 
                                              placeholder="Masukkan deskripsi perusahaan">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="form-text text-muted">
                                            <i class="fas fa-lightbulb mr-1"></i>Jelaskan profil dan keunggulan perusahaan
                                        </small>
                                        <small class="text-muted" id="charCount">0/1000 karakter</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_active" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-toggle-on text-success mr-2"></i>Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" 
                                            name="is_active" 
                                            id="is_active" 
                                            required>
                                        <option value="" hidden>Pilih status</option>
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>Status aktif akan menampilkan distributor di daftar
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('distributor.index') }}" class="btn btn-secondary btn-lg">
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

.custom-file-input:focus ~ .custom-file-label {
    border-color: #059669;
    box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
}

.btn-lg {
    border-radius: 8px;
    padding: 0.75rem 2rem;
}

.border-bottom {
    border-color: #e3e6f0 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const descriptionInput = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const imageInput = document.getElementById('image_name');
    const imageLabel = document.getElementById('image_name_label');

    // Character count for description
    descriptionInput.addEventListener('input', function() {
        const text = this.value;
        charCount.textContent = `${text.length}/1000 karakter`;
        
        // Change color when approaching limit
        if (text.length > 900) {
            charCount.className = 'text-warning';
        } else if (text.length > 980) {
            charCount.className = 'text-danger';
        } else {
            charCount.className = 'text-muted';
        }
    });

    // File input label update
    imageInput.addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'Pilih file logo...';
        imageLabel.textContent = fileName;
    });

    // Form validation
    const form = document.getElementById('distributorForm');
    form.addEventListener('submit', function(e) {
        const requiredFields = [
            'name', 'company_name', 'email', 'phone', 'address',
            'payment_term_id', 'delivery_method_id', 
            'business_scale_id', 'is_active'
        ];
        
        let isValid = true;
        let firstInvalidField = null;
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Validate email format
        const emailField = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailField.value && !emailRegex.test(emailField.value)) {
            isValid = false;
            emailField.classList.add('is-invalid');
            if (!firstInvalidField) {
                firstInvalidField = emailField;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            if (firstInvalidField) {
                firstInvalidField.focus();
            }
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Harap lengkapi semua field yang wajib diisi.',
                confirmButtonColor: '#059669'
            });
        }
    });

    // Initialize character count
    if (descriptionInput.value) {
        descriptionInput.dispatchEvent(new Event('input'));
    }
});

function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endsection