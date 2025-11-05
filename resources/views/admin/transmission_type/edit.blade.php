@extends('layouts.app')

@section('title', 'Transmisi')

@section('content')

    <x-alert />

    <div class="card">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Ubah Data Tipe Transmisi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.transmission_type.update', $transmissionType->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama Tipe Transmisi</label>
                    <input type="text" class="form-control" name="name" value="{{ $transmissionType->name }}" required>
                </div>

                <div class="form-group">
                    <x-button_back route="admin.transmission_type.index" />
                    <x-button_save />
                </div>
            </form>
        </div>
    </div>

@endsection
