@extends('layouts.app')

@section('title', 'Ubah Profil')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-circle text-primary mr-2"></i>Profil Pengguna
        </h1>
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-user-edit mr-2"></i>Ubah Profil
                    </h5>
                </div>
                <div class="card-body p-4">
                    <x-alert />
                    <x-user_form :id="auth()->user()->id" route="profile.update" :imageRequired="false" :isReadOnly="true" method="PUT"
                :withRole="true" :name="auth()->user()->name" :email="auth()->user()->email" :withBack="true" routeBack="user.index"
                :image="auth()->user()->image_name" :phone="auth()->user()->phone" :address="auth()->user()->address" :role="auth()->user()->is_admin" :passwordRequired="false" :deletePhotoProfile="true" />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var imgElement = document.getElementById('imagePreview');
        var imagePlaceholder = document.getElementById('imagePlaceholder');

        @if (auth()->user()->image_name)
            imgElement.src = "{{ asset('storage/user/' . auth()->user()->image_name) }}";
            imgElement.style.display = 'block';
            imagePlaceholder.style.display = 'none';
            
            var removePhotoBtn = document.getElementById('removePhotoBtn');
            if (removePhotoBtn) {
                removePhotoBtn.style.display = 'block';
            }
        @endif
    });
</script>
@endsection