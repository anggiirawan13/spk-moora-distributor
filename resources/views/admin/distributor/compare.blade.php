<!-- resources/views/admin/distributor/compare.blade.php -->
@extends('layouts.app')

@section('title', 'Bandingkan Dua Distributor')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Perbandingan Distributor</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4><strong>Distributor 1: {{ $distributor1->name }}</strong></h4>
                        <img src="{{ $distributor1->image_name ? asset('storage/distributor/' . $distributor1->image_name) : asset('img/default-image.png') }}" class="img-fluid rounded shadow my-3" alt="{{ $distributor1->name }}" style="max-height: 200px;">
                        <x-table_distributor :distributor="$distributor1" />
                    </div>
                    <div class="col-md-6">
                        <h4><strong>Distributor 2: {{ $distributor2->name }}</strong></h4>
                        <img src="{{ $distributor2->image_name ? asset('storage/distributor/' . $distributor2->image_name) : asset('img/default-image.png') }}" class="img-fluid rounded shadow my-3" alt="{{ $distributor2->name }}" style="max-height: 200px;">
                        <x-table_distributor :distributor="$distributor2" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('distributor.compare.form') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Pilih Distributor</a>
            </div>
        </div>
    </div>
@endsection