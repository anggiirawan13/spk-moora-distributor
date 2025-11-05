@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')

    <x-table title="Daftar Kategori Produk" createRoute="admin.product_category.create" showRoute="admin.product_category.show"
        editRoute="admin.product_category.edit" deleteRoute="admin.product_category.destroy" :data="$productCategories" 
        :columns="[['label' => 'Nama', 'field' => 'name'], ['label' => 'Deskripsi', 'field' => 'description']]" />

@endsection