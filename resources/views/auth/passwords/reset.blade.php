<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Pendukung Keputusan</title>

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

        .form-control-user {
            border-radius: 8px;
            padding: 12px 20px;
            border: 1px solid #d1d3e2;
            transition: all 0.3s ease;
        }

        .form-control-user:focus {
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
            margin-bottom: 10px;
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
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-5">

                        <div class="logo-container">
                            @if(file_exists(public_path('img/logo.jpg')))
                                <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="logo">
                            @else
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-lock fa-2x text-white"></i>
                                </div>
                            @endif
                        </div>

                        <div class="text-center">
                            <h1 class="h3 page-title">
                                <i class="fas fa-key text-primary mr-2"></i>Reset Password
                            </h1>
                            <p class="page-subtitle">
                                Masukkan password baru Anda
                            </p>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}" class="user" id="resetPasswordForm">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ request()->email }}">

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control form-control-user"
                                           placeholder="Password Baru"
                                           required
                                           autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control form-control-user"
                                    placeholder="Konfirmasi Password"
                                    required
                                    autocomplete="new-password">
                                </div>
                            </div>

                            <div id="passwordMatch" class="mt-2 font-weight-bold" style="display: none;"></div>

                            <div class="form-group mt-3">
                                <label class="font-weight-bold text-dark mb-2">
                                    <i class="fas fa-list-check mr-2"></i>Syarat Password:
                                </label>
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

                            <button type="submit" class="btn btn-primary btn-user btn-block mt-4" id="submitBtn">
                                <i class="fas fa-save mr-2"></i>Ubah Password
                            </button>
                        </form>

                        <hr class="divider">

                        <div class="text-center">
                            <a class="back-link" href="{{ route('login') }}">
                                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Halaman Login
                            </a>
                        </div>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt mr-1"></i>
                                Pastikan password Anda kuat dan mudah diingat
                            </small>
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
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetPasswordForm');
            const submitBtn = document.getElementById('submitBtn');
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const matchText = document.getElementById('passwordMatch');
            const minChar = document.getElementById('char');
            const hasUpper = document.getElementById('upper');
            const hasLower = document.getElementById('lower');
            const hasNumber = document.getElementById('number');
            const hasSpecial = document.getElementById('special');

            @if ($errors->any())
                const validationErrors = @json($errors->all());
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: validationErrors.join('\n'),
                    confirmButtonColor: '#dc3545'
                });
            @endif

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

            form.addEventListener('submit', function(e) {
                const password = passwordInput.value.trim();
                const confirmation = confirmInput.value.trim();
                const hasMinChar = password.length >= 8;
                const hasUpper = /[A-Z]/.test(password);
                const hasLower = /[a-z]/.test(password);
                const hasNumber = /\d/.test(password);
                const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

                if (!password || !confirmation) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Input Belum Lengkap',
                        text: 'Harap isi semua field password',
                        confirmButtonColor: '#047857'
                    });
                    return;
                }

                if (!hasMinChar || !hasUpper || !hasLower || !hasNumber || !hasSpecial) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Password Kurang Kuat',
                        text: 'Gunakan minimal 8 karakter, 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 karakter spesial',
                        confirmButtonColor: '#047857'
                    });
                    return;
                }

                if (password !== confirmation) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Sama',
                        text: 'Konfirmasi password harus sama dengan password baru',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }

                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                submitBtn.disabled = true;
            });

            function updateRequirement(element, condition, text) {
                if (!element) return;

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
                if (!passwordInput || !confirmInput || !matchText) return;

                const passwordVal = passwordInput.value;
                const confirmVal = confirmInput.value;

                if (confirmVal === '') {
                    matchText.style.display = 'none';
                    return;
                }

                matchText.style.display = 'block';

                if (passwordVal === confirmVal) {
                    matchText.innerHTML = '<i class="fas fa-check mr-1"></i>Password cocok';
                    matchText.classList.remove('text-danger');
                    matchText.classList.add('text-success');
                } else {
                    matchText.innerHTML = '<i class="fas fa-times mr-1"></i>Password tidak cocok';
                    matchText.classList.remove('text-success');
                    matchText.classList.add('text-danger');
                }
            }
        });
    </script>

</body>

</html>
