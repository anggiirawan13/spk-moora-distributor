@extends('layouts.app')

@section('title', 'Bahan Bakar')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Detail Bahan Bakar: {{ $fuelType->name }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <td>{{ $fuelType->name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $fuelType->created_at->format('d-m-Y H:i') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td>{{ $fuelType->updated_at->format('d-m-Y H:i') }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.fuel_type.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                    Kembali</a>
                @if (auth()->user()->is_admin == 1)
                    <x-button_edit route="admin.fuel_type.edit" :id="$fuelType->id" />
                @endif
            </div>
        </div>
    </div>

@endsection
