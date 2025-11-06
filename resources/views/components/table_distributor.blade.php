<div class="card shadow border-0">
    <div class="card-header bg-gradient-primary text-white py-3">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-info-circle mr-2"></i>Informasi Detail Distributor
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Informasi Kontak -->
            <div class="col-md-6">
                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-address-card mr-2"></i>Informasi Kontak
                </h6>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-start">
                        <div class="icon-container bg-light rounded p-2 mr-3">
                            <i class="fas fa-building text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-1">Nama Perusahaan</h6>
                            <p class="mb-0 text-muted">{{ $distributor->company_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="info-item mb-3">
                    <div class="d-flex align-items-start">
                        <div class="icon-container bg-light rounded p-2 mr-3">
                            <i class="fas fa-map-marker-alt text-success fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-1">Alamat</h6>
                            <p class="mb-0 text-muted">{{ $distributor->address }}</p>
                        </div>
                    </div>
                </div>

                <div class="info-item mb-3">
                    <div class="d-flex align-items-start">
                        <div class="icon-container bg-light rounded p-2 mr-3">
                            <i class="fas fa-phone text-info fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-1">Telepon</h6>
                            <p class="mb-0 text-muted">
                                <a href="tel:{{ $distributor->phone }}" class="text-decoration-none text-muted">
                                    {{ $distributor->phone }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="info-item mb-3">
                    <div class="d-flex align-items-start">
                        <div class="icon-container bg-light rounded p-2 mr-3">
                            <i class="fas fa-envelope text-warning fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-1">Email</h6>
                            <p class="mb-0 text-muted">
                                <a href="mailto:{{ $distributor->email }}" class="text-decoration-none text-muted">
                                    {{ $distributor->email }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="d-flex align-items-start">
                        <div class="icon-container bg-light rounded p-2 mr-3">
                            <i class="fas fa-toggle-on {{ $distributor->is_active ? 'text-success' : 'text-secondary' }} fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-1">Status</h6>
                            <p class="mb-0">
                                <span class="badge {{ $distributor->is_active ? 'badge-success' : 'badge-secondary' }} badge-pill">
                                    <i class="fas {{ $distributor->is_active ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                    {{ $distributor->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Bisnis -->
            <div class="col-md-6">
                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-chart-line mr-2"></i>Informasi Bisnis
                </h6>
                
                <div class="info-item mb-3">
                    <div class="d-flex align-items-start">
                        <div class="icon-container bg-light rounded p-2 mr-3">
                            <i class="fas fa-boxes text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-1">Produk</h6>
                            <p class="mb-0">
                                <span class="badge badge-primary badge-pill">
                                    {{ $distributor->product?->name ?? 'Tidak ada kategori' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="info-item mb-3">
                    <div class="d-flex align-items-start">
                        <div class="icon-container bg-light rounded p-2 mr-3">
                            <i class="fas fa-money-bill-wave text-success fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-1">Termin Pembayaran</h6>
                            <p class="mb-0">
                                <span class="badge badge-success badge-pill">
                                    {{ $distributor->paymentTerm?->name ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="info-item mb-3">
                    <div class="d-flex align-items-start">
                        <div class="icon-container bg-light rounded p-2 mr-3">
                            <i class="fas fa-shipping-fast text-info fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-1">Metode Pengiriman</h6>
                            <p class="mb-0">
                                <span class="badge badge-info badge-pill">
                                    {{ $distributor->deliveryMethod?->name ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="info-item mb-3">
                    <div class="d-flex align-items-start">
                        <div class="icon-container bg-light rounded p-2 mr-3">
                            <i class="fas fa-chart-line text-warning fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-1">Skala Bisnis</h6>
                            <p class="mb-0">
                                <span class="badge badge-warning badge-pill">
                                    {{ $distributor->businessScale?->name ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="info-item">
                    <div class="d-flex align-items-start">
                        <div class="icon-container bg-light rounded p-2 mr-3">
                            <i class="fas fa-clock text-secondary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-1">Informasi Sistem</h6>
                            <div class="small text-muted">
                                <div class="mb-1">
                                    <i class="fas fa-calendar-plus mr-1"></i>
                                    Dibuat: {{ $distributor->created_at->format('d M Y H:i') }}
                                </div>
                                <div>
                                    <i class="fas fa-calendar-check mr-1"></i>
                                    Diupdate: {{ $distributor->updated_at->format('d M Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi Perusahaan -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-left-info">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 font-weight-bold text-info">
                            <i class="fas fa-file-alt mr-2"></i>Deskripsi Perusahaan
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted" style="line-height: 1.6;">{{ $distributor->description ? $distributor->description : '-'}}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics (Optional) -->
        @if($distributor->alternatives && $distributor->alternatives->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-left-success">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 font-weight-bold text-success">
                            <i class="fas fa-chart-bar mr-2"></i>Statistik dalam Sistem
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="border-right">
                                    <h3 class="text-success font-weight-bold">{{ $distributor->alternatives->count() }}</h3>
                                    <p class="text-muted mb-0">Total Alternatif</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border-right">
                                    <h3 class="text-primary font-weight-bold">
                                        {{ $distributor->alternatives->avg('score') ? number_format($distributor->alternatives->avg('score'), 2) : '0.00' }}
                                    </h3>
                                    <p class="text-muted mb-0">Rata-rata Skor</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div>
                                    <h3 class="text-info font-weight-bold">
                                        #{{ $distributor->alternatives->min('rank') ?? '-' }}
                                    </h3>
                                    <p class="text-muted mb-0">Peringkat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
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

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-success {
    border-left: 4px solid #059669 !important;
}

.icon-container {
    min-width: 45px;
    text-align: center;
}

.info-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
}

.badge-pill {
    padding: 0.5em 1em;
    font-size: 0.85rem;
}

.border-right {
    border-right: 1px solid #e3e6f0 !important;
}

.border-bottom {
    border-color: #e3e6f0 !important;
}

/* Hover effects */
.info-item:hover {
    background-color: #f8f9fa;
    border-radius: 6px;
    padding: 0.5rem;
    margin: 0 -0.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .border-right {
        border-right: none !important;
        border-bottom: 1px solid #e3e6f0 !important;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
    }
}
</style>