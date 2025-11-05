@extends('layouts.app')

@section('title', 'Transmisi')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Detail Tipe Transmisi: {{ $transmissionType->name }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <td>{{ $transmissionType->name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $transmissionType->created_at->format('d-m-Y H:i') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td>{{ $transmissionType->updated_at->format('d-m-Y H:i') }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <x-button_back route="admin.transmission_type.index" />
                @if (auth()->user()->is_admin == 1)
                    <x-button_edit route="admin.transmission_type.edit" :id="$transmissionType->id" />
                @endif
            </div>
        </div>
    </div>

@endsection
