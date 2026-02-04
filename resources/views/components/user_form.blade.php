@props([
    'id' => null,
    'route' => '',
    'method' => 'POST',
    'imageRequired' => false,
    'passwordRequired' => false,
    'isReadOnly' => false,
    'withRole' => false,
    'withBack' => false,
    'routeBack' => '',
    'name' => '',
    'email' => '',
    'image' => '',
    'role' => 0,
    'deletePhotoProfile' => false,
    'phone' => '',
    'position' => '',
    'address' => ''
])

<form action="{{ $id ? route($route, $id) : route($route) }}" enctype="multipart/form-data" method="POST" id="userForm">
    @csrf
    @method($method)

    <div class="row">
        <div class="col-md-4 text-center mb-4">
            <div class="profile-image-container">
                <div class="position-relative d-inline-block">
                @if($image)
                    <img id="imagePreview" 
                        src="{{ asset('storage/user/' . $image) }}"
                        class="img-thumbnail rounded-circle shadow" 
                        style="width: 200px; height: 200px; object-fit: cover;"
                        alt="Foto Profil">
                @else
                    <img id="imagePreview" 
                        class="img-thumbnail rounded-circle shadow" 
                        style="width: 200px; height: 200px; object-fit: cover; display: none;"
                        alt="Foto Profil">
                        
                    <div id="imagePlaceholder" 
                        class="img-thumbnail rounded-circle bg-light d-flex align-items-center justify-content-center"
                        style="width: 200px; height: 200px; border: 2px dashed #dee2e6;">
                        <div class="text-center">
                            <i class="fas fa-user fa-3x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">Belum ada foto</p>
                        </div>
                    </div>
                @endif
            </div>
                
                <div class="mt-3">
                    <label for="image_name" class="btn btn-outline-primary btn-sm cursor-pointer mb-2">
                        <i class="fas fa-camera mr-1"></i>{{ $image ? 'Ubah Foto' : 'Upload Foto' }}
                        <input type="file" 
                               name="image_name" 
                               id="image_name" 
                               class="d-none" 
                               accept="image/*"
                               onchange="previewImage(event)"
                               {{ $imageRequired ? 'required' : '' }}>
                    </label>
                    <small class="form-text text-muted d-block">
                        Format: JPG, PNG, GIF (Maks. 2MB)
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="form-section">
                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Pribadi
                </h6>
                
                <div class="form-group">
                    <label for="name" class="font-weight-bold text-dark mb-2">
                        <i class="fas fa-user text-success mr-2"></i>Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           name="name" 
                           id="name"
                           value="{{ old('name', $name) }}" 
                           placeholder="Masukkan nama lengkap"
                           required
                           autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-envelope text-info mr-2"></i>Alamat Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email" 
                                id="email"
                                value="{{ old('email', $email) }}" 
                                placeholder="Masukkan alamat email"
                                required
                                {{ $isReadOnly ? 'readonly' : '' }}
                                style="background-color: #f8f9fa;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Email tidak dapat diubah
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone" class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-phone text-warning mr-2"></i>Nomor Telepon
                            </label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" 
                                   id="phone"
                                   value="{{ old('phone', $phone) }}" 
                                   placeholder="Masukkan nomor telepon">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="font-weight-bold text-dark mb-2">
                        <i class="fas fa-map-marker-alt text-danger mr-2"></i>Alamat
                    </label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              name="address" 
                              id="address"
                              rows="3" 
                              placeholder="Masukkan alamat lengkap">{{ old('address', $address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-section mt-4">
                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-lock mr-2"></i>Ganti Password
                </h6>
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle mr-1"></i>
                        Kosongkan field password jika tidak ingin mengubah password.
                    </small>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-key text-success mr-2"></i>Password Baru
                                {!! $passwordRequired ? '<span class="text-danger">*</span>' : '' !!}
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" 
                                   id="password"
                                   placeholder="Masukkan password baru"
                                   {{ $passwordRequired ? 'required' : '' }}>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation" class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-key text-warning mr-2"></i>Konfirmasi Password
                                {!! $passwordRequired ? '<span class="text-danger">*</span>' : '' !!}
                            </label>
                            <input type="password" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   name="password_confirmation" 
                                   id="password_confirmation"
                                   placeholder="Konfirmasi password baru"
                                   {{ $passwordRequired ? 'required' : '' }}>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="passwordMatch" class="mt-2 font-weight-bold" style="display: none;"></div>

                <div class="form-group mt-3">
                    <label class="font-weight-bold text-dark mb-2"><i class="fas fa-list-check mr-2"></i>Syarat Password:</label>
                    <ul id="passwordRequirements" class="list-unstyled text-sm pl-3">
                        <li id="char" class="text-danger">
                            <i class="fas fa-times mr-1"></i>Minimal 8 karakter
                        </li>
                        <li id="upper" class="text-danger">
                            <i class="fas fa-times mr-1"></i>Minimal 1 huruf besar
                        </li>
                        <li id="lower" class="text-danger">
                            <i class="fas fa-times mr-1"></i>Minimal 1 huruf kecil
                        </li>
                        <li id="number" class="text-danger">
                            <i class="fas fa-times mr-1"></i>Minimal 1 angka
                        </li>
                        <li id="special" class="text-danger">
                            <i class="fas fa-times mr-1"></i>Minimal 1 karakter spesial
                        </li>
                    </ul>
                </div>
            </div>
            @if ($withRole)
            <div class="form-section mt-4">
                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-user-tag mr-2"></i>Role Pengguna
                </h6>
                <div class="form-group">
                    <label for="role" class="font-weight-bold text-dark mb-2">
                        <i class="fas fa-shield-alt text-info mr-2"></i>Role
                    </label>
                    <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                        <option value="1" {{ old('role', $role) == 1 ? 'selected' : '' }}>Admin</option>
                        <option value="0" {{ old('role', $role) == 0 ? 'selected' : '' }}>Staf</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                @if ($withBack)
                    <a href="{{ route($routeBack) }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                @else
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                @endif
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

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menghapus foto profil? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i>Ya, Hapus
                    </button>
                </form>
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

.form-control:read-only {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

.btn-lg {
    border-radius: 8px;
    padding: 0.75rem 2rem;
}

.profile-image-container {
    position: relative;
}

.cursor-pointer {
    cursor: pointer;
}

.form-section {
    margin-bottom: 2rem;
}

.border-bottom {
    border-color: #e3e6f0 !important;
}

#passwordRequirements li {
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.text-sm {
    font-size: 0.875rem;
}

select { cursor: pointer !important; }
</style>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');
    const removeBtn = document.getElementById('removePhotoBtn');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.setAttribute('style', 'display: none !important');
            if (removeBtn) removeBtn.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

function confirmDelete(url) {
    document.getElementById('deleteForm').action = url;
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    confirmModal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const matchText = document.getElementById('passwordMatch');
    const minChar = document.getElementById('char');
    const hasUpper = document.getElementById('upper');
    const hasLower = document.getElementById('lower');
    const hasNumber = document.getElementById('number');
    const hasSpecial = document.getElementById('special');

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const val = passwordInput.value;

            updateRequirement(minChar, val.length >= 8, 'Minimal 8 karakter');
            updateRequirement(hasUpper, /[A-Z]/.test(val), 'Minimal 1 huruf besar');
            updateRequirement(hasLower, /[a-z]/.test(val), 'Minimal 1 huruf kecil');
            updateRequirement(hasNumber, /\d/.test(val), 'Minimal 1 angka');
            updateRequirement(hasSpecial, /[!@#$%^&*(),.?":{}|<>]/.test(val), 'Minimal 1 karakter spesial');

            checkPasswordMatch();
        });
    }

    if (confirmInput) {
        confirmInput.addEventListener('input', checkPasswordMatch);
    }

    function updateRequirement(element, condition, text) {
        if (condition) {
            element.innerHTML = '<i class="fas fa-check mr-1"></i>' + text;
            element.classList.remove('text-danger');
            element.classList.add('text-success');
        } else {
            element.innerHTML = '<i class="fas fa-times mr-1"></i>' + text;
            element.classList.remove('text-success');
            element.classList.add('text-danger');
        }
    }

    function checkPasswordMatch() {
        if (!passwordInput || !confirmInput) return;

        const passwordVal = passwordInput.value;
        const confirmVal = confirmInput.value;

        if (confirmVal === '') {
            matchText.style.display = 'none';
            return;
        }

        matchText.style.display = 'block';

        if (passwordVal === confirmVal) {
            matchText.textContent = '✅ Password cocok';
            matchText.classList.remove('text-danger');
            matchText.classList.add('text-success');
        } else {
            matchText.textContent = '❌ Password tidak cocok';
            matchText.classList.remove('text-success');
            matchText.classList.add('text-danger');
        }
    }

    const form = document.getElementById('userForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!name) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Nama lengkap harus diisi!',
                    confirmButtonColor: '#059669'
                });
                document.getElementById('name').focus();
            }
        });
    }
});
</script>