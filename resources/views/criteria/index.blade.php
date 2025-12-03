@extends('layouts.app')

@section('title', 'Kriteria')

@section('content')

    <x-table title="Daftar Kriteria" createRoute="criteria.create" showRoute="criteria.show" editRoute="criteria.edit"
        deleteRoute="criteria.destroy" :data="$criterias" :columns="[
            ['label' => 'Kode', 'field' => 'code'],
            ['label' => 'Nama', 'field' => 'name'],
            ['label' => 'Bobot', 'field' => 'weight'],
            ['label' => 'Atribut', 'field' => 'attribute_type'],
        ]" />

@endsection
