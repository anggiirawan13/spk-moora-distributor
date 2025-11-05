@extends('layouts.app')

@section('title', 'Skala Bisnis')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Detail Skala Bisnis: {{ $businessScale->name }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <td>{{ $businessScale->name }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $businessScale->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $businessScale->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td>{{ $businessScale->updated_at->format('d-m-Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <x-button_back route="admin.business_scale.index" />
                @if (auth()->user()->is_admin == 1)
                    <x-button_edit route="admin.business_scale.edit" :id="$businessScale->id" />
                @endif
            </div>
        </div>
    </div>

@endsection