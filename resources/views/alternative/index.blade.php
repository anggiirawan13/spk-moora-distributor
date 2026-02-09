@extends('layouts.app')

@section('title', 'Alternatif')

@section('content')
<div class="container-fluid">
    <x-alert />

    <div class="card shadow mb-4 border-0">
        <div class="card-header text-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="text-primary m-0 font-weight-bold">
                    <i class="fas fa-warehouse mr-2"></i>Daftar Alternatif
                </h5>
                @auth
                @if (auth()->user()->is_admin == 1)
                    <a href="{{ route('alternative.create') }}" id="btn-add-data" class="btn btn-primary btn-sm text-white">
                        <i class="fas fa-plus-circle mr-1"></i>Tambah Data
                    </a>
                @endif
            @endauth
            </div>
        </div>

        <div class="card-body shadow mb-4 border-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped" width="100%" id="dataTable" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center text-white" width="60px">No</th>
                            <th class="text-center text-white">Nama Alternatif</th>
                            @foreach ($criterias as $criteria)
                                <th class="text-center text-white" width="120px">
                                    <div class="text-center">
                                        <div class="font-weight-bold small">{{ $criteria->name }}</div>
                                        <div class="text-white smaller">({{ $criteria->code }})</div>
                                        <div class="mt-1">
                                            <span class="badge badge-sm {{ $criteria->attribute_type == 'Benefit' ? 'badge-success' : 'badge-danger' }}">
                                                {{ $criteria->attribute_type == 'Benefit' ? 'B' : 'C' }}
                                            </span>
                                            <span class="badge badge-sm badge-warning">
                                                {{ $criteria->weight }}
                                            </span>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                            <th class="text-center text-white" width="120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alternatives as $index => $item)
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="badge badge-primary badge-circle">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="font-weight-bold text-dark">{{ $item['name'] }}</div>
                                    <small class="text-muted">
                                        {{ $item['code'] ?? 'Distributor' }}
                                    </small>
                                </td>
                                @foreach ($criterias as $criteria)
                                    <td class="text-center align-middle">
                                        @if(isset($item[$criteria->id]) && $item[$criteria->id] != '-')
                                            <span class="badge badge-light border text-dark">
                                                {{ $item[$criteria->id] }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('alternative.show', $item['id']) }}"
                                           class="btn btn-info btn-sm m-1"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @auth
                                            @if (auth()->user()->is_admin == 1)
                                                <a href="{{ route('alternative.edit', $item['id']) }}"
                                                   class="btn btn-primary btn-sm m-1"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button type="button" 
                                                        class="btn btn-danger btn-sm m-1 js-confirm-delete"
                                                        data-url="{{ route('alternative.destroy', $item['id']) }}"
                                                        data-name="{{ $item['name'] }}"
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
                                <td colspan="{{ count($criterias) + 4 }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3"></i>
                                        <p class="mb-1 font-weight-bold">Belum Ada Data Alternatif</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.table thead tr th {
    border-bottom: 2px solid #059669;
    background: #059669; 
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

.badge-sm {
    font-size: 0.7em;
    padding: 0.3em 0.6em;
}

.smaller {
    font-size: 0.75rem;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group-sm > .btn {
    border-radius: 6px;
    margin: 0 2px;
}

.border-left-primary {
    border-left: 4px solid #047857 !important;
}

.border-left-success {
    border-left: 4px solid #059669 !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-warning {
    border-left: 4px solid #f59e0b !important;
}
</style>

<script>
function confirmDelete(url, name) {
    const submitDelete = () => {
        const form = document.getElementById('deleteForm');
        form.action = url;
        form.submit();
    };

    if (window.Swal && typeof Swal.fire === 'function') {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Anda akan menghapus alternatif "${name}". Semua data penilaian yang terkait juga akan dihapus.`,
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
                submitDelete();
            }
        });
        return;
    }

    if (confirm(`Anda akan menghapus alternatif "${name}". Semua data penilaian yang terkait juga akan dihapus.`)) {
        submitDelete();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    document.addEventListener('click', function(event) {
        var trigger = event.target.closest('.js-confirm-delete');
        if (!trigger) {
            return;
        }
        event.preventDefault();
        var url = trigger.getAttribute('data-url') || '';
        var name = trigger.getAttribute('data-name') || 'Data';
        confirmDelete(url, name);
    });
});
</script>
@endsection
