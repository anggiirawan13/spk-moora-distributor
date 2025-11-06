<div class="card shadow border-0">
    <div class="card-header bg-gradient-primary text-white py-3">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-info-circle mr-2"></i>Informasi Distributor
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="row">
            <div class="col-md-4 border-right">
                <div class="text-center p-4">
                    @if($distributor->image_name)
                        <img src="{{ asset('storage/distributor/' . $distributor->image_name) }}" 
                             alt="{{ $distributor->name }}" 
                             class="img-fluid rounded-circle shadow mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-warehouse fa-3x text-muted"></i>
                        </div>
                    @endif
                    <h4 class="font-weight-bold text-primary">{{ $distributor->name }}</h4>
                    <p class="text-muted mb-2">{{ $distributor->company_name }}</p>
                    <span class="badge badge-pill {{ $distributor->is_active ? 'badge-success' : 'badge-secondary' }} badge-lg">
                        <i class="fas {{ $distributor->is_active ? 'fa-check' : 'fa-times' }} mr-1"></i>
                        {{ $distributor->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-2 mr-3">
                                    <i class="fas fa-building text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-1">Nama Perusahaan</h6>
                                    <p class="mb-0 text-muted">{{ $distributor->company_name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-2 mr-3">
                                    <i class="fas fa-map-marker-alt text-success fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-1">Alamat</h6>
                                    <p class="mb-0 text-muted">{{ $distributor->address }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-2 mr-3">
                                    <i class="fas fa-phone text-info fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-1">Telepon</h6>
                                    <p class="mb-0 text-muted">{{ $distributor->phone }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-2 mr-3">
                                    <i class="fas fa-envelope text-warning fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-1">Email</h6>
                                    <p class="mb-0 text-muted">{{ $distributor->email }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-2 mr-3">
                                    <i class="fas fa-boxes text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-1">Kategori Produk</h6>
                                    <p class="mb-0">
                                        <span class="badge badge-light border">
                                            {{ $distributor->productCategory?->name ?? 'N/A' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-2 mr-3">
                                    <i class="fas fa-money-bill-wave text-success fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-1">Termin Pembayaran</h6>
                                    <p class="mb-0">
                                        <span class="badge badge-light border">
                                            {{ $distributor->paymentTerm?->name ?? 'N/A' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-2 mr-3">
                                    <i class="fas fa-shipping-fast text-info fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-1">Metode Pengiriman</h6>
                                    <p class="mb-0">
                                        <span class="badge badge-light border">
                                            {{ $distributor->deliveryMethod?->name ?? 'N/A' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-2 mr-3">
                                    <i class="fas fa-chart-line text-warning fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-1">Skala Bisnis</h6>
                                    <p class="mb-0">
                                        <span class="badge badge-light border">
                                            {{ $distributor->businessScale?->name ?? 'N/A' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($distributor->description)
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="font-weight-bold text-dark mb-2">
                            <i class="fas fa-file-alt mr-2 text-primary"></i>Deskripsi Perusahaan
                        </h6>
                        <div class="bg-light rounded p-3">
                            <p class="mb-0 text-muted">{{ $distributor->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
}

.badge-lg {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.bg-light.rounded {
    background-color: #f8f9fa !important;
    min-width: 45px;
    text-align: center;
}

.border-right {
    border-right: 1px solid #e3e6f0 !important;
}

.badge-light.border {
    border: 1px solid #dee2e6 !important;
    background-color: white;
}
</style>