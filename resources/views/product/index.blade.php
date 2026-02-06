@extends('layouts.app')

@section('title', 'Produk')

@section('content')

    <x-table title="Daftar Produk" createRoute="product.create" showRoute="product.show"
        editRoute="product.edit" deleteRoute="product.destroy" :data="$products" 
        :columns="[
            ['label' => 'Kode', 'field' => 'code'],
            ['label' => 'Nama', 'field' => 'name'],
            ['label' => 'Deskripsi', 'field' => 'description']
        ]" />

@endsection
