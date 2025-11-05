@extends('layouts.app')

@section('title', 'Merek Mobil')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Detail Merek Mobil: {{ $carBrand->name }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <td>{{ $carBrand->name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $carBrand->created_at->format('d-m-Y H:i') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td>{{ $carBrand->updated_at->format('d-m-Y H:i') }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.car_brand.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                    Kembali</a>
                @if (auth()->user()->is_admin == 1)
                <x-button_edit route="admin.car_brand.edit" :id="$carBrand->id" />
                @endif
            </div>
        </div>
    </div>

@endsection
