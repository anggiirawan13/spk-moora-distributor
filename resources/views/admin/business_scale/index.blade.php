@extends('layouts.app')

@section('title', 'Skala Bisnis')

@section('content')

    <x-table title="Daftar Skala Bisnis" createRoute="admin.business_scale.create"
        showRoute="admin.business_scale.show" editRoute="admin.business_scale.edit"
        deleteRoute="admin.business_scale.destroy" :data="$businessScales" :columns="[['label' => 'Nama', 'field' => 'name'], ['label' => 'Deskripsi', 'field' => 'description']]" />

@endsection