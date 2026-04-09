@extends('layouts.app')

@section('title', 'History Import')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-history text-success mr-2"></i>History Import
        </h1>
        <a href="{{ route('import.excel.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Import
        </a>
    </div>

    <x-alert />

    <div class="card shadow border-0 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('import.excel.history') }}">
                <div class="row align-items-end">
                    <div class="col-md-8 mb-3 mb-md-0">
                        <label for="search" class="font-weight-bold text-dark">Cari Batch</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $search }}"
                            class="form-control"
                            placeholder="Cari nomor batch, nama file, atau importer">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success mr-2">
                            <i class="fas fa-search mr-1"></i>Cari
                        </button>
                        <a href="{{ route('import.excel.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt mr-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @forelse ($importBatches as $index => $entry)
        @php
            $batch = $entry['batch'];
            $items = $entry['items'];
            $summary = $entry['summary'];
            $collapseId = 'batchHistory' . $batch->id;
            $isExpanded = $index === 0;
        @endphp

        <div class="card shadow-sm border-0 mb-4 history-batch-card">
            <div class="card-header bg-gradient-primary text-white py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <h5 class="mb-1 font-weight-bold">
                            <i class="fas fa-file-import mr-2"></i>Batch #{{ $batch->id }}
                        </h5>
                        <small>
                            File: {{ $batch->original_file_name ?: '-' }} |
                            Importer: {{ $batch->importedBy->name ?? '-' }} |
                            {{ $batch->created_at?->format('d-m-Y H:i') }}
                        </small>
                    </div>
                    <div class="d-flex flex-wrap align-items-center">
                        <span class="badge badge-light text-dark mr-2 mb-1">Total {{ $summary['total'] }}</span>
                        <span class="badge badge-warning mr-2 mb-1">Pending {{ $summary['pending'] }}</span>
                        <span class="badge badge-success mr-2 mb-1">Approved {{ $summary['approved'] }}</span>
                        <span class="badge badge-danger mr-2 mb-1">Reject {{ $summary['rejected'] }}</span>
                        <button
                            class="btn btn-light btn-sm mb-1"
                            type="button"
                            data-toggle="collapse"
                            data-target="#{{ $collapseId }}"
                            aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                            aria-controls="{{ $collapseId }}">
                            <i class="fas {{ $isExpanded ? 'fa-chevron-up' : 'fa-chevron-down' }} mr-1"></i>{{ $isExpanded ? 'Sembunyikan' : 'Lihat Detail' }}
                        </button>
                    </div>
                </div>
            </div>

            <div id="{{ $collapseId }}" class="collapse {{ $isExpanded ? 'show' : '' }}">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 js-batch-table" data-batch-id="{{ $batch->id }}">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tipe</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr class="js-batch-row" data-batch-id="{{ $batch->id }}">
                                        <td>{{ $item['type_label'] }}</td>
                                        <td>{{ $item['code'] }}</td>
                                        <td class="font-weight-bold">{{ $item['name'] }}</td>
                                        <td><x-approval_badge :status="$item['approval_status_label']" /></td>
                                        <td>
                                            <small class="{{ $item['approval_reason'] ? 'text-danger' : 'text-muted' }}">
                                                {{ $item['approval_reason'] ?: '-' }}
                                            </small>
                                        </td>
                                        <td class="text-center" style="min-width: 180px;">
                                            @if ($item['edit_url'] || $item['delete_url'])
                                                <div class="d-flex flex-wrap justify-content-center">
                                                    @if ($item['edit_url'])
                                                        <a href="{{ $item['edit_url'] }}" class="btn btn-outline-primary btn-sm mr-2 mb-2">
                                                            <i class="fas fa-edit mr-1"></i>Edit
                                                        </a>
                                                    @endif
                                                    @if ($item['delete_url'])
                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-danger btn-sm mb-2 js-confirm-delete"
                                                            data-url="{{ $item['delete_url'] }}"
                                                            data-name="{{ $item['display_name'] }}">
                                                            <i class="fas fa-trash mr-1"></i>Hapus
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <small class="text-muted">Tidak ada aksi</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($items->count() > 5)
                        <div class="d-flex flex-wrap justify-content-between align-items-center px-3 py-3 border-top bg-light js-batch-pagination"
                            data-batch-id="{{ $batch->id }}"
                            data-per-page="5">
                            <div class="d-flex flex-wrap align-items-center">
                                <small class="text-muted js-batch-pagination-info mr-3 mb-2 mb-md-0"></small>
                                <div class="d-flex align-items-center mb-2 mb-md-0">
                                    <label class="small text-muted mb-0 mr-2" for="perPage{{ $batch->id }}">Per page</label>
                                    <select id="perPage{{ $batch->id }}" class="form-control form-control-sm js-batch-per-page" style="width: 90px;">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center mt-2 mt-md-0">
                                <button type="button" class="btn btn-outline-secondary js-batch-prev">
                                    <i class="fas fa-chevron-left mr-1"></i>Prev
                                </button>
                                <div class="btn-group btn-group-sm mx-2 js-batch-page-numbers" role="group"></div>
                                <button type="button" class="btn btn-outline-secondary js-batch-next">
                                    Next<i class="fas fa-chevron-right ml-1"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-light border">
            Tidak ada history import yang sesuai.
        </div>
    @endforelse

    @if ($importBatches->hasPages())
        <div class="d-flex justify-content-end mt-4">
            {{ $importBatches->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

<script>
function submitDeleteImport(url) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';

    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}

document.addEventListener('click', function(event) {
    const trigger = event.target.closest('.js-confirm-delete');
    if (!trigger) {
        return;
    }

    event.preventDefault();
    const url = trigger.getAttribute('data-url') || '';
    const name = trigger.getAttribute('data-name') || 'Data';

    if (window.Swal && typeof Swal.fire === 'function') {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash mr-1"></i>Ya, Hapus',
            cancelButtonText: '<i class="fas fa-times mr-1"></i>Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitDeleteImport(url);
            }
        });
        return;
    }

    if (confirm(`Apakah Anda yakin ingin menghapus "${name}"?`)) {
        submitDeleteImport(url);
    }
});

document.addEventListener('show.bs.collapse', function(event) {
    const button = document.querySelector(`[data-target="#${event.target.id}"]`);
    if (!button) {
        return;
    }

    button.setAttribute('aria-expanded', 'true');
    button.innerHTML = '<i class="fas fa-chevron-up mr-1"></i>Sembunyikan';
});

document.addEventListener('hide.bs.collapse', function(event) {
    const button = document.querySelector(`[data-target="#${event.target.id}"]`);
    if (!button) {
        return;
    }

    button.setAttribute('aria-expanded', 'false');
    button.innerHTML = '<i class="fas fa-chevron-down mr-1"></i>Lihat Detail';
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.js-batch-pagination').forEach(function(container) {
        const batchId = container.getAttribute('data-batch-id');
        const rows = Array.from(document.querySelectorAll(`.js-batch-row[data-batch-id="${batchId}"]`));
        const info = container.querySelector('.js-batch-pagination-info');
        const prevButton = container.querySelector('.js-batch-prev');
        const nextButton = container.querySelector('.js-batch-next');
        const pageNumbers = container.querySelector('.js-batch-page-numbers');
        const perPageSelect = container.querySelector('.js-batch-per-page');
        let currentPage = 1;
        let perPage = parseInt(container.getAttribute('data-per-page') || '5', 10);

        const totalPages = function() {
            return Math.max(1, Math.ceil(rows.length / perPage));
        };

        const renderPageButtons = function() {
            const pages = totalPages();
            pageNumbers.innerHTML = '';
            const visiblePages = [];

            if (pages <= 7) {
                for (let i = 1; i <= pages; i++) {
                    visiblePages.push(i);
                }
            } else {
                visiblePages.push(1);

                if (currentPage > 3) {
                    visiblePages.push('ellipsis-start');
                }

                const start = Math.max(2, currentPage - 1);
                const end = Math.min(pages - 1, currentPage + 1);

                for (let i = start; i <= end; i++) {
                    visiblePages.push(i);
                }

                if (currentPage < pages - 2) {
                    visiblePages.push('ellipsis-end');
                }

                visiblePages.push(pages);
            }

            visiblePages.forEach(function(item) {
                if (typeof item !== 'number') {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'btn btn-sm btn-light disabled';
                    ellipsis.textContent = '...';
                    pageNumbers.appendChild(ellipsis);
                    return;
                }

                const button = document.createElement('button');
                button.type = 'button';
                button.className = `btn btn-sm ${item === currentPage ? 'btn-success' : 'btn-outline-secondary'}`;
                button.textContent = item;
                button.addEventListener('click', function() {
                    renderPage(item);
                });
                pageNumbers.appendChild(button);
            });
        };

        const renderPage = function(page) {
            currentPage = page;

            rows.forEach(function(row, index) {
                const rowPage = Math.floor(index / perPage) + 1;
                row.style.display = rowPage === currentPage ? '' : 'none';
            });

            const start = rows.length === 0 ? 0 : ((currentPage - 1) * perPage) + 1;
            const end = Math.min(currentPage * perPage, rows.length);
            info.textContent = `Menampilkan ${start}-${end} dari ${rows.length} data`;

            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages();
            renderPageButtons();
        };

        prevButton.addEventListener('click', function() {
            if (currentPage > 1) {
                renderPage(currentPage - 1);
            }
        });

        nextButton.addEventListener('click', function() {
            if (currentPage < totalPages()) {
                renderPage(currentPage + 1);
            }
        });

        perPageSelect.addEventListener('change', function() {
            perPage = parseInt(this.value || '5', 10);
            currentPage = 1;
            renderPage(1);
        });

        renderPage(1);
    });
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
}

.history-batch-card {
    border-radius: 12px;
    overflow: hidden;
}
</style>
@endsection
