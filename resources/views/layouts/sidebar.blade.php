<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('login') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/logo.jpg') }}" alt="logo" width="40" height="40"
                class="img-fluid rounded-circle">
        </div>
        <div class="sidebar-brand-text mx-3">
            <div class="font-weight-bold">SPK MOORA</div>
            <small class="text-light">PT Anugrah Hadi Electric</small>
        </div>
    </a>

    <hr class="sidebar-divider my-2">

    @auth
        <li class="nav-item active">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider my-2">

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#dataMaster"
                aria-expanded="false" aria-controls="dataMaster">
                <i class="fas fa-database"></i>
                <span>Data Master</span>
                <i class="fas float-right mt-1"></i>
            </a>
            <div id="dataMaster" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item text-dark" href="{{ route('business_scale.index') }}">
                        <i class="fas fa-chart-line mr-2"></i> Skala Bisnis
                    </a>
                    <a class="collapse-item text-dark" href="{{ route('delivery_method.index') }}">
                        <i class="fas fa-shipping-fast mr-2"></i> Metode Pengiriman
                    </a>
                    <a class="collapse-item text-dark" href="{{ route('payment_term.index') }}">
                        <i class="fas fa-money-bill-wave mr-2"></i> Termin Pembayaran
                    </a>
                    <a class="collapse-item text-dark" href="{{ route('distributor.index') }}">
                        <i class="fas fa-warehouse"></i> Distributor
                    </a>
                    <a class="collapse-item text-dark" href="{{ route('product.index') }}">
                        <i class="fas fa-boxes mr-2"></i> Produk
                    </a>
                </div>
            </div>
        </li>
    @endauth

    <li class="nav-item">
        <a class="nav-link" href="{{ route('distributor.compare.form') }}">
            <i class="fas fa-balance-scale"></i>
            <span>Perbandingan</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#dataPenunjang"
            aria-expanded="false" aria-controls="dataPenunjang">
            <i class="fas fa-cogs"></i>
            <span>Kriteria & Alternatif</span>
            <i class="fas float-right mt-1"></i>
        </a>
        <div id="dataPenunjang" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item text-dark" href="{{ route('criteria.index') }}">
                    <i class="fas fa-list mr-2"></i>Kriteria
                </a>
                <a class="collapse-item text-dark" href="{{ route('subcriteria.index') }}">
                    <i class="fas fa-stream mr-2"></i>Sub Kriteria
                </a>
                <a class="collapse-item text-dark" href="{{ route('alternative.index') }}">
                    <i class="fas fa-th mr-2"></i>Alternatif
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('moora.calculation') }}">
            <i class="fas fa-calculator"></i>
            <span>Perhitungan MOORA</span>
        </a>
    </li>

    @can('admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.index') }}">
                <i class="fas fa-users"></i>
                <span>Manajemen User</span>
            </a>
        </li>
    @endcan

    <hr class="sidebar-divider my-2">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('profile.edit') }}">
            <i class="fas fa-user-cog"></i>
            <span>Profile Settings</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" style="background: rgba(255,255,255,0.1);">
            <i class="text-white"></i>
        </button>
    </div>

</ul>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
    }

    .sidebar-brand-text {
        line-height: 1.2;
    }

    .sidebar .nav-item .nav-link {
        padding: 0.8rem 1rem;
        margin: 0.1rem 0.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .sidebar .nav-item .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    .sidebar .nav-item.active .nav-link {
        background: rgba(255, 255, 255, 0.15);
        border-left: 4px solid #10b981;
    }

    .collapse-item {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .collapse-item:hover {
        background: rgba(255, 255, 255, 0.1);
        text-decoration: none;
    }

    .sidebar-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
<!-- End of Sidebar -->
