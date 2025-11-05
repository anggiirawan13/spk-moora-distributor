<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('login') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/logo.jpg') }}" alt="logo" width="40" height="40" class="img-fluid">
        </div>
        <div class="sidebar-brand-text mx-3">SPK Moora</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @auth
        @can('admin')
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Data Master (Dropdown) -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#dataMaster"
                    aria-expanded="false" aria-controls="dataMaster">
                    <i class="fas fa-database"></i>
                    <span>Data Master</span>
                </a>
                <div id="dataMaster" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('admin.business_scale.index') }}">
                            <i class="fas fa-chart-line"></i> Skala Bisnis
                        </a>
                        <a class="collapse-item" href="{{ route('admin.delivery_method.index') }}">
                            <i class="fas fa-shipping-fast"></i> Metode Pengiriman
                        </a>
                        <a class="collapse-item" href="{{ route('admin.product_category.index') }}">
                            <i class="fas fa-boxes"></i> Kategori Produk
                        </a>
                        <a class="collapse-item" href="{{ route('admin.payment_term.index') }}">
                            <i class="fas fa-money-bill-wave"></i> Termin Pembayaran
                        </a>
                    </div>
                </div>
            </li>
        @endcan
    @endauth

    <!-- Distributor -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('distributor.index') }}">
            <i class="fas fa-warehouse"></i>
            <span>Distributor</span>
        </a>
    </li>

    <!-- Perbandingan Distributor -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('distributor.compare.form') }}">
            <i class="fas fa-balance-scale"></i>
            <span>Perbandingan Distributor</span>
        </a>
    </li>

    <!-- Proses Perhitungan -->
    @cannot('admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('calculation.user') }}">
                <i class="fas fa-bullseye"></i>
                <span>Rekomendasi Distributor</span>
            </a>
        </li>
    @endcannot

    @can('admin')
        <!-- Rekomendasi Distributor (Dropdown) -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#dataPenunjang"
                aria-expanded="false" aria-controls="dataPenunjang">
                <i class="fas fa-bullseye"></i>
                <span>Rekomendasi Distributor</span>
            </a>
            <div id="dataPenunjang" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('admin.criteria.index') }}">
                        <i class="fas fa-list"></i> Kriteria
                    </a>
                    <a class="collapse-item" href="{{ route('admin.subcriteria.index') }}">
                        <i class="fas fa-stream"></i> Sub Kriteria
                    </a>
                    <a class="collapse-item" href="{{ route('admin.alternative.index') }}">
                        <i class="fas fa-th"></i> Alternatif
                    </a>
                </div>
            </div>
        </li>

        <!-- Proses Perhitungan -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.calculation') }}">
                <i class="fas fa-calculator"></i>
                <span>Hitung</span>
            </a>
        </li>

        <!-- Manajemen Pengguna (Dropdown untuk Admin) -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.user.index') }}">
                <i class="fas fa-users"></i>
                <span>Data User</span>
            </a>
        </li>
    @endcan

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->