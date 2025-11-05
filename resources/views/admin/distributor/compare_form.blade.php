<!-- resources/views/admin/distributor/compare_form.blade.php -->
@extends('layouts.app')

@section('title', 'Pilih Distributor untuk Dibandingkan')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Pilih Dua Distributor untuk Dibandingkan</h5>
            </div>
            <div class="card-body">

                <x-alert />

                <form action="{{ route('distributor.compare') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="distributor1">Distributor Pertama</label>
                        <select name="distributor1" id="distributor1" class="form-control" required>
                            <option value="">Pilih Distributor</option>
                            @foreach ($distributors as $distributor)
                                <option value="{{ $distributor->id }}">{{ $distributor->name }} - {{ $distributor->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="distributor2">Distributor Kedua</label>
                        <select name="distributor2" id="distributor2" class="form-control" required>
                            <option value="">Pilih Distributor</option>
                            @foreach ($distributors as $distributor)
                                <option value="{{ $distributor->id }}">{{ $distributor->name }} - {{ $distributor->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-exchange-alt"></i> Bandingkan
                        Distributor</button>
                </form>
            </div>
        </div>
    </div>
@endsection