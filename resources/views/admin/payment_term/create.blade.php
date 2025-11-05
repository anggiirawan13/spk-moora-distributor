@extends('layouts.app')

@section('title', 'Termin Pembayaran')

@section('content')

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    <div class="card">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Tambah Data Termin Pembayaran</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.payment_term.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nama Termin Pembayaran</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                    <small class="form-text text-muted">Deskripsi singkat tentang termin pembayaran</small>
                </div>

                <div class="form-group">
                    <x-button_back route="admin.payment_term.index" />
                    <x-button_save />
                </div>
            </form>
        </div>
    </div>

@endsection