@extends('layouts.app')

@section('title', 'Metode Pengiriman')

@section('content')

    <x-table title="Daftar Metode Pengiriman" createRoute="delivery_method.create" showRoute="delivery_method.show"
        editRoute="delivery_method.edit" deleteRoute="delivery_method.destroy" :data="$deliveryMethods" 
        :columns="[['label' => 'Nama', 'field' => 'name'], ['label' => 'Deskripsi', 'field' => 'description']]" />

@endsection