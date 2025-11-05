@extends('layouts.app')

@section('title', 'Distributor Barang Elektrikal')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Detail Distributor: {{ $distributor->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ $distributor->image_name ? asset('storage/distributor/' . $distributor->image_name) : asset('img/default-image.png') }}"
                            class="img-fluid rounded shadow" alt="{{ $distributor->name }}" style="max-height: 300px;">
                    </div>
                    <div class="col-md-8">
                        <x-table_distributor :distributor="$distributor" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <x-button_back route="distributor.index" />
                @if (auth()->user()->is_admin == 1)
                    <x-button_edit route="distributor.edit" :id="$distributor->id" />
                @endif
            </div>
        </div>
    </div>

@endsection