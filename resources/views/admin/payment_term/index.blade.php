@extends('layouts.app')

@section('title', 'Termin Pembayaran')

@section('content')

    <x-table title="Daftar Termin Pembayaran" createRoute="admin.payment_term.create" showRoute="admin.payment_term.show"
        editRoute="admin.payment_term.edit" deleteRoute="admin.payment_term.destroy" :data="$paymentTerms" 
        :columns="[['label' => 'Nama', 'field' => 'name'], ['label' => 'Deskripsi', 'field' => 'description']]" />

@endsection