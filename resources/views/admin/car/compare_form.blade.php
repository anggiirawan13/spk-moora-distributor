<!-- resources/views/admin/car/compare_form.blade.php -->
@extends('layouts.app')

@section('title', 'Pilih Mobil untuk Dibandingkan')

@section('content')


    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Pilih Dua Mobil untuk Dibandingkan</h5>
            </div>
            <div class="card-body">

                <x-alert />

                <form action="{{ route('car.compare') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="car1">Mobil Pertama</label>
                        <select name="car1" id="car1" class="form-control" required>
                            <option value="">Pilih Mobil</option>
                            @foreach ($cars as $car)
                                <option value="{{ $car->id }}">{{ $car->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="car2">Mobil Kedua</label>
                        <select name="car2" id="car2" class="form-control" required>
                            <option value="">Pilih Mobil</option>
                            @foreach ($cars as $car)
                                <option value="{{ $car->id }}">{{ $car->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-exchange-alt"></i> Bandingkan
                        Mobil</button>
                </form>
            </div>
        </div>
    </div>
@endsection
