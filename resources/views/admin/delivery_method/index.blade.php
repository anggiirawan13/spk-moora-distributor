@extends('layouts.app')

@section('title', 'Metode Pengiriman')

@section('content')

    <x-table title="Daftar Metode Pengiriman" createRoute="admin.delivery_method.create" showRoute="admin.delivery_method.show"
        editRoute="admin.delivery_method.edit" deleteRoute="admin.delivery_method.destroy" :data="$deliveryMethods" 
        :columns="[['label' => 'Nama', 'field' => 'name'], ['label' => 'Deskripsi', 'field' => 'description']]" />

@endsection