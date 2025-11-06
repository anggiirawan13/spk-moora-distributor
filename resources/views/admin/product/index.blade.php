@extends('layouts.app')

@section('title', 'Produk')

@section('content')

    <x-table title="Daftar Produk" createRoute="admin.product.create" showRoute="admin.product.show"
        editRoute="admin.product.edit" deleteRoute="admin.product.destroy" :data="$products" 
        :columns="[['label' => 'Nama', 'field' => 'name'], ['label' => 'Deskripsi', 'field' => 'description']]" />

@endsection