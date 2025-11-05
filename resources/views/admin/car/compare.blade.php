<!-- resources/views/admin/car/compare.blade.php -->
@extends('layouts.app')

@section('title', 'Bandingkan Dua Mobil')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Perbandingan Mobil</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4><strong>Mobil 1: {{ $car1->name }}</strong></h4>
                        <img src="{{ $car1->image_name ? asset('storage/car/' . $car1->image_name) : asset('img/default-image.png') }}" class="img-fluid rounded shadow my-3" alt="{{ $car1->name }}">
                        <x-table_car :car="$car1" />
                    </div>
                    <div class="col-md-6">
                        <h4><strong>Mobil 2: {{ $car2->name }}</strong></h4>
                        <img src="{{ $car2->image_name ? asset('storage/car/' . $car2->image_name) : asset('img/default-image.png') }}" class="img-fluid rounded shadow my-3" alt="{{ $car2->name }}">
                        <x-table_car :car="$car2" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('car.compare.form') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Pilih Mobil</a>
            </div>
        </div>
    </div>
@endsection
