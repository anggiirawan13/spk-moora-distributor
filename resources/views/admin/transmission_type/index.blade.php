@extends('layouts.app')

@section('title', 'Transmisi')

@section('content')

    <x-table title="Daftar Tipe Transmisi" createRoute="admin.transmission_type.create"
        showRoute="admin.transmission_type.show" editRoute="admin.transmission_type.edit"
        deleteRoute="admin.transmission_type.destroy" :data="$transmissionTypes" :columns="[['label' => 'Nama', 'field' => 'name']]" />

@endsection
