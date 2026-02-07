@extends('layouts.app')

@section('title', 'Skala Bisnis')

@section('content')

    <x-table title="Daftar Skala Bisnis" createRoute="business_scale.create"
        showRoute="business_scale.show" editRoute="business_scale.edit"
        deleteRoute="business_scale.destroy" :data="$businessScales" :columns="[['label' => 'Kode', 'field' => 'code'],['label' => 'Nama', 'field' => 'name'], ['label' => 'Deskripsi', 'field' => 'description']]" />

@endsection