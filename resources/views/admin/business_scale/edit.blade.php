@extends('layouts.app')

@section('title', 'Skala Bisnis')

@section('content')

    <x-alert />

    <div class="card">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Ubah Data Skala Bisnis</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.business_scale.update', $businessScale->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama Skala Bisnis</label>
                    <input type="text" class="form-control" name="name" value="{{ $businessScale->name }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="description" rows="3">{{ $businessScale->description }}</textarea>
                    <small class="form-text text-muted">Deskripsi singkat tentang skala bisnis</small>
                </div>

                <div class="form-group">
                    <x-button_back route="admin.business_scale.index" />
                    <x-button_save />
                </div>
            </form>
        </div>
    </div>

@endsection