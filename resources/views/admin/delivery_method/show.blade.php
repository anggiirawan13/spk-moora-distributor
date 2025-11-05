@extends('layouts.app')

@section('title', 'Metode Pengiriman')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Detail Metode Pengiriman: {{ $deliveryMethod->name }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <td>{{ $deliveryMethod->name }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $deliveryMethod->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $deliveryMethod->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td>{{ $deliveryMethod->updated_at->format('d-m-Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.delivery_method.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                    Kembali</a>
                @if (auth()->user()->is_admin == 1)
                    <x-button_edit route="admin.delivery_method.edit" :id="$deliveryMethod->id" />
                @endif
            </div>
        </div>
    </div>

@endsection