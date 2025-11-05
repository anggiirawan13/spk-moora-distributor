@extends('layouts.app')

@section('title', 'Jenis Mobil')

@section('content')

    <x-table title="Daftar Jenis Mobil" createRoute="admin.car_type.create" showRoute="admin.car_type.show"
        editRoute="admin.car_type.edit" deleteRoute="admin.car_type.destroy" :data="$carTypes" :columns="[['label' => 'Nama', 'field' => 'name']]" />

@endsection
