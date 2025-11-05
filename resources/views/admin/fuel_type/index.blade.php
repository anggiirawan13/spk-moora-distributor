@extends('layouts.app')

@section('title', 'Bahan Bakar')

@section('content')

    <x-table title="Daftar Bahan Bakar" createRoute="admin.fuel_type.create" showRoute="admin.fuel_type.show"
        editRoute="admin.fuel_type.edit" deleteRoute="admin.fuel_type.destroy" :data="$fuelTypes" :columns="[['label' => 'Nama', 'field' => 'name']]" />

@endsection
