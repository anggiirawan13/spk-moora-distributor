<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi - Sistem Pendukung Keputusan</title>

    <link rel="icon" href="{{ asset('img/logo.jpg') }}?v=2.0" type="image/jpeg">

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 20px;
            border: 1px solid #d1d3e2;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #047857;
            box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e3e6f0;
        }

        .page-title {
            color: #047857;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .back-link {
            color: #047857;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #059669;
            text-decoration: underline;
        }

        .divider {
            border-color: #e3e6f0;
            margin: 25px 0;
        }

        .profile-image-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e3e6f0;
            display: none;
        }

        .profile-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .upload-btn {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            transform: translateY(-2px);
        }

        #passwordRequirements {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        #passwordRequirements li {
            margin-bottom: 5px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 5;
        }

        .form-control.with-icon {
            padding-left: 45px;
        }

        .bg-register-image {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            background-size: cover;
            background-position: center;
        }

        @media (max-width: 991px) {
            .bg-register-image {
                height: 200px;
            }
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image">
                        <div class="d-flex align-items-center justify-content-center h-100 text-white p-5">
                            <div class="text-center">
                                <i class="fas fa-user-plus fa-4x mb-4"></i>
                                <h3 class="font-weight-bold">Bergabung Dengan Kami</h3>
                                <p class="mb-0">Daftar akun untuk mengakses sistem pendukung keputusan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            
                            <div class="logo-container">
                                @if(file_exists(public_path('img/logo.jpg')))
                                    <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="logo">
                                @else
                                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-user-plus fa-2x text-white"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="text-center">
                                <h1 class="h3 page-title">
                                    <i class="fas fa-user-plus text-primary mr-2"></i>Buat Akun Baru
                                </h1>
                                <p class="page-subtitle">
                                    Isi form berikut untuk membuat akun baru
                                </p>
                            </div>

                            <x-alert />

                            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" id="registerForm">
                                @csrf

                                <div class="profile-image-container">
                                    <div class="position-relative d-inline-block">
                                        <img id="imagePreview" class="profile-preview" alt="Preview Foto Profil">
                                        <div id="imagePlaceholder" class="profile-placeholder">
                                            <div class="text-center">
                                                <i class="fas fa-user fa-2x text-muted mb-2"></i>
                                                <p class="text-muted small mb-0">Belum ada foto</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <label for="image_name" class="btn btn-outline-primary btn-sm upload-btn mb-2">
                                            <i class="fas fa-camera mr-1"></i>Pilih Foto Profil
                                            <input type="file" 
                                                   name="image_name" 
                                                   id="image_name" 
                                                   class="d-none" 
                                                   accept="image/*"
                                                   onchange="previewImage(event)">
                                        </label>
                                        <small class="form-text text-muted d-block">
                                            Format: JPG, PNG, GIF (Maks. 2MB)
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-user text-success mr-2"></i>Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" 
                                               name="name" 
                                               id="name"
                                               class="form-control with-icon @error('name') is-invalid @enderror"
                                               placeholder="Masukkan nama lengkap" 
                                               value="{{ old('name') }}" 
                                               required
                                               autofocus>
                                    </div>
                                    @error('name')
                                        <small class="text-danger mt-2 d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-envelope text-info mr-2"></i>Alamat Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="email" 
                                               name="email" 
                                               id="email"
                                               class="form-control with-icon @error('email') is-invalid @enderror"
                                               placeholder="Masukkan alamat email" 
                                               value="{{ old('email') }}" 
                                               required>
                                    </div>
                                    @error('email')
                                        <small class="text-danger mt-2 d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-lock text-warning mr-2"></i>Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <i class="fas fa-key input-icon"></i>
                                        <input type="password" 
                                               name="password" 
                                               id="password"
                                               class="form-control with-icon @error('password') is-invalid @enderror"
                                               placeholder="Masukkan password" 
                                               required>
                                    </div>
                                    @error('password')
                                        <small class="text-danger mt-2 d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation" class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-lock text-primary mr-2"></i>Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <i class="fas fa-key input-icon"></i>
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation"
                                               class="form-control with-icon @error('password_confirmation') is-invalid @enderror"
                                               placeholder="Ulangi password" 
                                               required>
                                    </div>
                                    @error('password_confirmation')
                                        <small class="text-danger mt-2 d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div id="passwordMatch" class="mt-2 font-weight-bold" style="display: none;"></div>

                                <div class="form-group mt-3">
                                    <label class="font-weight-bold text-dark mb-2">
                                        <i class="fas fa-list-check mr-2"></i>Syarat Password:
                                    </label>
                                    <ul id="passwordRequirements" class="text-sm pl-3">
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

                                <button type="submit" class="btn btn-primary btn-block mt-4" id="submitBtn">
                                    <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                                </button>
                            </form>

                            <hr class="divider">

                            <div class="text-center">
                                <a class="back-link" href="{{ route('password.request') }}">
                                    <i class="fas fa-key mr-1"></i>Lupa Password?
                                </a>
                            </div>
                            <div class="text-center mt-2">
                                <a class="back-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt mr-1"></i>Sudah punya akun? Login!
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('imagePlaceholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                };
                
                reader.readAsDataURL(input.files[0]);
            }
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
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');

            passwordInput.addEventListener('input', function() {
                const val = passwordInput.value;

                updateRequirement(minChar, val.length >= 8, 'Minimal 8 karakter');
                updateRequirement(hasUpper, /[A-Z]/.test(val), 'Minimal 1 huruf besar');
                updateRequirement(hasLower, /[a-z]/.test(val), 'Minimal 1 huruf kecil');
                updateRequirement(hasNumber, /\d/.test(val), 'Minimal 1 angka');
                updateRequirement(hasSpecial, /[!@#$%^&*(),.?":{}|<>]/.test(val), 'Minimal 1 karakter spesial');

                checkPasswordMatch();
            });

            confirmInput.addEventListener('input', checkPasswordMatch);

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

            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const password = passwordInput.value;
                
                if (!name || !email || !password) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        text: 'Harap lengkapi semua field yang wajib diisi!',
                        confirmButtonColor: '#047857'
                    });
                    return;
                }

                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mendaftarkan...';
                submitBtn.disabled = true;
            });

            document.getElementById('email').addEventListener('blur', function() {
                const email = this.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email && !emailRegex.test(email)) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = email ? '#28a745' : '#d1d3e2';
                }
            });
        });
    </script>
</body>

</html>