@extends('layouts.app')

@section('content')
    <style>
        nav svg {
            height: 20px;
        }

        nav.hidden {
            display: block;
        }

        th {
            font-size: 0.875em;
        }

        .modal-content {
            transform: scale(0.8);
            transition: transform 0.3s ease-in-out;
        }

        .modal.show .modal-content {
            transform: scale(1);
        }
    </style>
    <x-table title="Daftar Distributor Barang Elektrikal" createRoute="distributor.create" showRoute="distributor.show" 
        editRoute="distributor.edit" deleteRoute="distributor.destroy" :data="$distributors" :columns="[
            ['label' => 'Nama Distributor', 'field' => 'name'],
            ['label' => 'Logo', 'field' => 'image', 'html' => true],
            ['label' => 'Nama Perusahaan', 'field' => 'company_name'],
            ['label' => 'Telepon', 'field' => 'phone'],
            ['label' => 'Email', 'field' => 'email'],
            ['label' => 'Termin Pembayaran', 'field' => 'payment_term'],
            ['label' => 'Metode Pengiriman', 'field' => 'delivery_method'],
            ['label' => 'Skala Bisnis', 'field' => 'business_scale'],
            ['label' => 'Status', 'field' => 'is_active'],
        ]" />

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