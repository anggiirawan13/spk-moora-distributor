@extends('layouts.app')

@section('title', 'Merek Mobil')

@section('content')

    <x-table title="Daftar Merek Mobil" createRoute="admin.car_brand.create" showRoute="admin.car_brand.show"
        editRoute="admin.car_brand.edit" deleteRoute="admin.car_brand.destroy" :data="$carBrands" :columns="[['label' => 'Nama', 'field' => 'name']]" />

@endsection
