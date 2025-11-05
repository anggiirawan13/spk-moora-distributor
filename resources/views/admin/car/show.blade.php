@extends('layouts.app')

@section('title', 'Mobil Bekas')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Detail Mobil: {{ $car->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ $car->image_name ? asset('storage/car/' . $car->image_name) : asset('img/default-image.png') }}"
                            class="img-fluid rounded shadow" alt="{{ $car->name }}">
                    </div>
                    <div class="col-md-8">
                        <x-table_car :car="$car" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <x-button_back route="car.index" />
                @if (auth()->user()->is_admin == 1)
                    <x-button_edit route="car.edit" :id="$car->id" />
                @endif
            </div>
        </div>
    </div>

@endsection
