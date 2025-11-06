@extends('layouts.app')

@section('title', 'Sub Kriteria')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-layer-group text-primary mr-2"></i>Sub Kriteria
        </h1>
        <div>
            <a href="{{ route('admin.subcriteria.index') }}" class="btn btn-secondary btn-sm mr-2">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>
    </div>

    <x-alert />

    @foreach ($criteria as $item)
    <div class="card shadow border-0 mb-4">
        <!-- Card Header -->
        <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-list-alt mr-2"></i>{{ $item->name }}
                    </h5>
                    <div class="mt-1">
                        <span class="badge badge-light mr-2">
                            <i class="fas fa-code mr-1"></i>Kode: {{ $item->code }}
                        </span>
                        <span class="badge badge-light mr-2">
                            <i class="fas fa-weight-hanging mr-1"></i>Bobot: {{ $item->weight }}
                        </span>
                        <span class="badge {{ $item->attribute_type == 'Benefit' ? 'badge-success' : 'badge-danger' }}">
                            <i class="fas fa-chart-bar mr-1"></i>
                            {{ $item->attribute_type }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.subcriteria.create', ['criteria_id' => $item->id]) }}"
                    class="btn btn-light btn-sm font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i>Tambah
                </a>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th>Nama Sub Kriteria</th>
                            <th class="text-center" style="width: 100px;">Nilai</th>
                            <th class="text-center" style="width: 120px;">Status</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($item->subCriteria as $sub)
                        <tr>
                            <td class="text-center align-middle">
                                <span class="badge badge-primary badge-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <div class="font-weight-bold text-dark">{{ $sub->name }}</div>
                                @if($sub->description)
                                <small class="text-muted">{{ Str::limit($sub->description, 50) }}</small>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge badge-success badge-pill" style="font-size: 1em;">
                                    {{ $sub->value }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge {{ $sub->is_active ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $sub->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.subcriteria.show', $sub->id) }}"
                                        class="btn btn-info btn-sm m-1"
                                        data-bs-toggle="tooltip"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @auth
                                    @if (auth()->user()->is_admin == 1)
                                    <a href="{{ route('admin.subcriteria.edit', $sub->id) }}"
                                        class="btn btn-primary btn-sm m-1"
                                        data-bs-toggle="tooltip"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button type="button" 
                                            class="btn btn-danger btn-sm m-1"
                                            onclick="confirmDelete('{{ route('admin.subcriteria.destroy', $sub->id) }}', '{{ $sub->name }}')"
                                            data-bs-toggle="tooltip"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                    @endauth
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-3"></i>
                                    <p class="mb-0">Belum ada sub kriteria</p>
                                    <small>Klik tombol "Tambah Sub Kriteria" untuk menambahkan data</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card Footer -->
        @if($item->subCriteria->count() > 0)
        <div class="card-footer bg-light py-2">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Total: <strong>{{ $item->subCriteria->count() }}</strong> sub kriteria
                    </small>
                </div>
                <div class="col-md-6 text-right">
                    <small class="text-muted">
                        Nilai range: 
                        <strong>{{ $item->subCriteria->min('value') }} - {{ $item->subCriteria->max('value') }}</strong>
                    </small>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endforeach

    @if($criteria->isEmpty())
    <div class="card shadow border-0">
        <div class="card-body text-center py-5">
            <div class="text-muted">
                <i class="fas fa-list-alt fa-3x mb-3"></i>
                <h5 class="font-weight-bold">Belum Ada Kriteria</h5>
                <p class="mb-0">Silakan buat kriteria terlebih dahulu untuk menambahkan sub kriteria</p>
                <a href="{{ route('admin.criteria.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus mr-1"></i>Tambah Kriteria
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.badge-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.badge-pill {
    padding: 0.5em 0.8em;
    border-radius: 50rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    color: #6c757d;
}

.btn-group-sm > .btn {
    border-radius: 6px;
    margin: 0 2px;
}

.card-footer {
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(url, name) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `
            <div class="text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5 class="font-weight-bold">Hapus Sub Kriteria?</h5>
                <p class="mb-0">Anda akan menghapus sub kriteria:</p>
                <p class="font-weight-bold text-danger">"${name}"</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger btn-lg',
            cancelButton: 'btn btn-secondary btn-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    });
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush