@props(['status' => '-'])

@php
    $statusLabel = $status ?: '-';
    $statusClass = 'badge-secondary';
    $icon = 'fa-info-circle';

    if (str_contains($statusLabel, 'Ditolak')) {
        $statusClass = 'badge-danger';
        $icon = 'fa-times-circle';
    } elseif (str_contains($statusLabel, 'Disetujui')) {
        $statusClass = 'badge-success';
        $icon = 'fa-check-circle';
    } elseif (str_contains($statusLabel, 'Menunggu')) {
        $statusClass = 'badge-warning';
        $icon = 'fa-clock';
    } elseif ($statusLabel === 'Data Manual' || $statusLabel === 'Data Aktif') {
        $statusClass = 'badge-info';
        $icon = 'fa-database';
    }
@endphp

<span class="badge badge-pill {{ $statusClass }}">
    <i class="fas {{ $icon }} mr-1"></i>{{ $statusLabel }}
</span>
