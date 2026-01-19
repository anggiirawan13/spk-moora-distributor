@extends('layouts.app')

@section('content')
    <x-table title="Daftar Akun" createRoute="user.create" showRoute="user.show" editRoute="user.edit"
        deleteRoute="user.destroy" :data="$users" :columns="[
            ['label' => 'Foto', 'field' => 'image', 'html' => true],
            ['label' => 'Nama', 'field' => 'name'],
            ['label' => 'Email', 'field' => 'email'],
            ['label' => 'Peran', 'field' => 'is_admin'],
        ]" />
@endsection

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