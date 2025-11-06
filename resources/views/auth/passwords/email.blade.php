<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Pendukung Keputusan</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.jpg') }}?v=2.0" type="image/jpeg">

    <!-- Font Awesome -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    
    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    
    <!-- SweetAlert2 -->
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

        .form-control-user.with-icon {
            padding-left: 45px;
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-xl-5 col-lg-6 col-md-8">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-5">
                        
                        <!-- Logo & Header -->
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
                                Masukkan email Anda untuk menerima link reset password
                            </p>
                        </div>

                        <!-- Reset Password Form -->
                        <form class="user" method="POST" action="{{ route('password.email') }}" id="resetPasswordForm">
                            @csrf

                            <div class="form-group">
                                    <input type="email" 
                                           class="form-control form-control-user with-icon" 
                                           name="email" 
                                           id="email"
                                           placeholder="Masukkan alamat email Anda"
                                           value="{{ old('email') }}"
                                           required 
                                           autofocus
                                           autocomplete="email">
                                @error('email')
                                    <small class="text-danger mt-2 d-block">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-user btn-block mt-4" id="submitBtn">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Link Reset Password
                            </button>
                        </form>

                        <hr class="divider">

                        <div class="text-center">
                            <a class="back-link" href="{{ route('login') }}">
                                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Halaman Login
                            </a>
                        </div>

                        <!-- Additional Help -->
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Link reset password akan dikirim ke email Anda
                            </small>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- SB Admin 2 JS -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- SweetAlert2 Alert Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetPasswordForm');
            const submitBtn = document.getElementById('submitBtn');

            // SweetAlert2 configurations
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: `{!! session('status') !!}`,
                    confirmButtonColor: '#047857',
                    confirmButtonText: 'Mengerti'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#dc3545'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    confirmButtonColor: '#dc3545'
                });
            @endif

            // Form submission handler
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                
                if (!email) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Email Kosong',
                        text: 'Harap masukkan alamat email Anda',
                        confirmButtonColor: '#047857'
                    });
                    return;
                }

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
                submitBtn.disabled = true;
            });

            // Email validation on input
            document.getElementById('email').addEventListener('input', function() {
                const email = this.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email && !emailRegex.test(email)) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = email ? '#28a745' : '#d1d3e2';
                }
            });

            // Enter key support
            document.getElementById('email').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    form.dispatchEvent(new Event('submit'));
                }
            });
        });
    </script>

</body>

</html>