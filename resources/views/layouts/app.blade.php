<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AHE - @yield('title', 'Dashboard')</title>

    <link rel="icon" href="{{ asset('img/logo.jpg') }}">
    
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" type="text/css">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    
    <link rel="stylesheet" href="{{ asset('css/sb-admin-2.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @stack('styles')
</head>

<body id="page-top">

    <div id="wrapper">

        @include('layouts.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                @include('layouts.navbar')

                <div class="container-fluid">
                    @yield('content')
                </div>

            </div>

            @include('layouts.footer')

        </div>

    </div>

    @include('layouts.scripts')

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('scripts')
</body>

</html>