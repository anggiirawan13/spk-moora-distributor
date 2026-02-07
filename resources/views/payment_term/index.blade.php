@extends('layouts.app')

@section('title', 'Termin Pembayaran')

@section('content')

    <x-table title="Daftar Termin Pembayaran" createRoute="payment_term.create" showRoute="payment_term.show"
        editRoute="payment_term.edit" deleteRoute="payment_term.destroy" :data="$paymentTerms" 
        :columns="[['label' => 'Kode', 'field' => 'code'],['label' => 'Nama', 'field' => 'name'], ['label' => 'Deskripsi', 'field' => 'description']]" />

@endsection