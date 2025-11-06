<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SPK Moora - PT Anugrah Hadi Electric</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.jpg') }}">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-green: #059669;
            --dark-green: #047857;
            --light-green: #10b981;
            --accent-teal: #0d9488;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        
        .bg-login-image {
            background: url("{{ asset('img/logo.jpg') }}") no-repeat center;
            background-size: contain;
            background-color: #f8f9fc;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(5, 150, 105, 0.1);
        }
        
        .form-control {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 12px 20px;
        }
        
        .form-control:focus {
            border-color: var(--light-green);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }
        
        .text-primary {
            color: var(--primary-green) !important;
        }
        
        .small {
            color: var(--accent-teal);
        }
        
        .small:hover {
            color: var(--dark-green);
        }
        
        .login-brand {
            font-weight: 700;
            color: var(--dark-green);
            font-size: 1.5rem;
        }
        
        .welcome-text {
            color: #374151;
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-xl-5 col-lg-6 col-md-8">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <div class="bg-login-image mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%;"></div>
                                        <h1 class="login-brand mb-2">SPK MOORA</h1>
                                        <p class="welcome-text">PT Anugrah Hadi Electric</p>
                                        <small class="text-muted">Sistem Rekomendasi Distributor</small>
                                    </div>
                                    
                                    <x-alert />

                                    <form class="user" method="post" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <input type="email" class="form-control form-control-user" name="email"
                                                placeholder="Email Address" required autofocus>
                                        </div>
                                        <div class="form-group mb-4">
                                            <input type="password" class="form-control form-control-user" name="password"
                                                placeholder="Password" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block btn-lg" name="login">
                                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                                        </button>
                                    </form>
                                    
                                    <hr class="my-4">
                                    
                                    <div class="text-center">
                                        <a class="small d-block mb-2" href="{{ route('password.request') }}">
                                            <i class="fas fa-key mr-1"></i>Forgot Password?
                                        </a>
                                        <a class="small" href="{{ route('register') }}">
                                            <i class="fas fa-user-plus mr-1"></i>Create an Account
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

</body>

</html>