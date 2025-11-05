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
    <x-table title="Daftar Mobil Bekas" createRoute="car.create" showRoute="car.show" editRoute="car.edit"
        deleteRoute="car.destroy" :data="$cars" :columns="[
            ['label' => 'Nama Mobil', 'field' => 'name'],
            ['label' => 'Foto', 'field' => 'image', 'html' => true],
            ['label' => 'Harga (Rp)', 'field' => 'price'],
            ['label' => 'Tahun Produksi', 'field' => 'manufacture_year'],
            ['label' => 'Jarak Tempuh (km)', 'field' => 'mileage'],
            ['label' => 'Bahan Bakar', 'field' => 'fuel_type'],
            ['label' => 'Kapasitas Mesin (cc)', 'field' => 'engine_capacity'],
            ['label' => 'Jumlah Kursi', 'field' => 'seat_count'],
            ['label' => 'Transmisi', 'field' => 'transmission_type'],
            ['label' => 'Warna', 'field' => 'color'],
            ['label' => 'Status Ketersediaan', 'field' => 'is_available'],
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
        function showImage(namaMobil, src) {
            document.getElementById('imageModalLabel').innerText = namaMobil;
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
