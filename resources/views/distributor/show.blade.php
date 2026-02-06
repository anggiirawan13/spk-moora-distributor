@extends('layouts.app')

@section('title', 'Detail Distributor Barang Elektrikal')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-truck-loading text-primary mr-2"></i>Detail Distributor
        </h1>
        <a href="{{ route('distributor.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-eye mr-2"></i>Detail Distributor: {{ $distributor->name }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-12 text-center mb-4">
                            <div class="profile-image-container">
                                <a href="#" data-toggle="modal" data-target="#imageModal" onclick="showImage('{{ $distributor->name }}', '{{ $distributor->image_name ? asset('storage/distributor/' . $distributor->image_name) : asset('img/default-image.jpg') }}')">
                                    <img src="{{ $distributor->image_name ? asset('storage/distributor/' . $distributor->image_name) : asset('img/default-image.jpg') }}"
                                    class="img-thumbnail rounded-circle shadow" 
                                    alt="{{ $distributor->name }}" 
                                    style="width: 200px; height: 200px; object-fit: cover;">
                                </a>
                                <div class="mt-3">
                                    <h4 class="font-weight-bold text-primary mb-1">{{ $distributor->name }}</h4>
                                    <p class="text-muted mb-0">Kode: {{ $distributor->code }}</p>
                                    <p class="text-muted mb-0">NPWP: {{ $distributor->npwp_formatted ?: 'Tidak diisi' }}</p>
                                    <span class="badge {{ $distributor->is_active ? 'badge-success' : 'badge-secondary' }} mt-2">
                                        {{ $distributor->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <x-table_distributor :distributor="$distributor" />
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('distributor.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <div>
                                    @if (auth()->user()->is_admin == 1)
                                    <a href="{{ route('distributor.edit', $distributor->id) }}" class="btn btn-primary btn-lg mr-2">
                                        <i class="fas fa-edit mr-2"></i>Edit
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bootstrap -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel"></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded shadow-lg"
                        style="max-height: 80vh; transition: 0.3s;">
                </div>
            </div>
        </div>
    </div>

<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%) !important;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.btn-lg {
    border-radius: 8px;
    padding: 0.75rem 2rem;
}

.profile-image-container {
    position: relative;
}

.img-thumbnail {
    border: 3px solid #e3e6f0;
    transition: all 0.3s ease;
}

.img-thumbnail:hover {
    border-color: #059669;
    transform: scale(1.05);
}
</style>

<script>
    function showImage(namaDistributor, src) {
        document.getElementById('imageModalLabel').innerText = namaDistributor;
        document.getElementById('modalImage').src = src;
    }

    document.addEventListener("DOMContentLoaded", function() {
        var modal = document.getElementById("imageModal");

        modal.addEventListener("keydown", function(event) {
            if (event.key === "Escape") {
                var modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
            }
        });

        var closeButton = document.querySelector("#imageModal .btn-close");
        closeButton.addEventListener("click", function() {
            var modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();
        });
    });
</script>
@endsection
