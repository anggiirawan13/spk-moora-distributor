@extends('layouts.app')

@section('title', 'Approval Import')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-check-double text-success mr-2"></i>Approval Import
        </h1>
        <a href="{{ route('import.excel.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <x-alert />

    <div class="card shadow border-0 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('import.approvals.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label for="status" class="font-weight-bold text-dark">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="">Semua</option>
                            @foreach ($statusOptions as $option)
                                <option value="{{ $option['value'] }}" {{ $selectedStatus === $option['value'] ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="type" class="font-weight-bold text-dark">Tipe</label>
                        <select id="type" name="type" class="form-control">
                            <option value="">Semua</option>
                            @foreach ($typeOptions as $option)
                                <option value="{{ $option['value'] }}" {{ $selectedType === $option['value'] ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="approval_status" class="font-weight-bold text-dark">Status Approval</label>
                        <select id="approval_status" name="approval_status" class="form-control">
                            <option value="">Semua</option>
                            @foreach ($approvalStatusOptions as $option)
                                <option value="{{ $option['value'] }}" {{ $selectedApprovalStatus === $option['value'] ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success mr-2">
                            <i class="fas fa-filter mr-1"></i>Filter
                        </button>
                        <a href="{{ route('import.approvals.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt mr-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @forelse ($batches as $entry)
        @php
            $batch = $entry['batch'];
            $items = $entry['items'];
            $status = $entry['status'];
            $canApproveBatch = auth()->user()->is_admin == 1
                ? $items->where('can_approve_admin', true)->isNotEmpty()
                : $items->where('can_approve_director', true)->isNotEmpty();
            $canRejectBatch = auth()->user()->is_admin == 1
                ? $items->where('can_reject_admin', true)->isNotEmpty()
                : $items->where('can_reject_director', true)->isNotEmpty();
            $batchRoute = auth()->user()->is_admin == 1
                ? route('import.approvals.batch_admin', $batch)
                : route('import.approvals.batch_director', $batch);
            $rejectBatchRoute = auth()->user()->is_admin == 1
                ? route('import.approvals.batch_admin_reject', $batch)
                : route('import.approvals.batch_director_reject', $batch);
            $collapseId = 'approvalBatch' . $batch->id;
            $targetBatch = request('batch');
            $isExpanded = (string) $targetBatch === (string) $batch->id || ($loop->first && !$targetBatch);
        @endphp

        <div class="card shadow border-0 mb-4 history-batch-card">
            <div class="card-header bg-gradient-primary text-white py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fas fa-file-import mr-2"></i>Batch #{{ $batch->id }}
                        </h5>
                        <small>
                            File: {{ $batch->original_file_name ?: '-' }} |
                            Importer: {{ $batch->importedBy->name ?? '-' }} |
                            {{ $batch->created_at?->format('d-m-Y H:i') }}
                        </small>
                        <div class="mt-2">
                            <x-approval_badge :status="$status['label']" />
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center">
                        @if ($canApproveBatch)
                            <form action="{{ $batchRoute }}" method="POST" class="mr-2 mb-1">
                                @csrf
                                <input type="hidden" name="batch" value="{{ $batch->id }}">
                                <button type="submit" class="btn btn-light btn-sm font-weight-bold">
                                    <i class="fas fa-check mr-1"></i>Approve Batch
                                </button>
                            </form>
                        @endif
                        @if ($canRejectBatch)
                            <form action="{{ $rejectBatchRoute }}" method="POST" class="mr-2 mb-1 d-flex align-items-center">
                                @csrf
                                <input type="text" name="reason" class="form-control form-control-sm mr-2" placeholder="Alasan batch" required style="width: 180px;">
                                <button type="submit" class="btn btn-danger btn-sm font-weight-bold">
                                    <i class="fas fa-times mr-1"></i>Reject Batch
                                </button>
                            </form>
                        @endif
                        <button
                            class="btn btn-light btn-sm mb-1"
                            type="button"
                            data-toggle="collapse"
                            data-target="#{{ $collapseId }}"
                            aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                            aria-controls="{{ $collapseId }}">
                            <i class="fas {{ $isExpanded ? 'fa-chevron-up' : 'fa-chevron-down' }} mr-1"></i>{{ $isExpanded ? 'Sembunyikan' : 'Detail' }}
                        </button>
                    </div>
                </div>
            </div>
            <div id="{{ $collapseId }}" class="collapse {{ $isExpanded ? 'show' : '' }}">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 70px;">No</th>
                                    <th>Tipe</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr class="js-approval-row" data-batch-id="{{ $batch->id }}" data-row-index="{{ $loop->index + 1 }}">
                                        <td class="text-center font-weight-bold js-approval-number">{{ $loop->index + 1 }}</td>
                                        <td>{{ $item['type_label'] }}</td>
                                        <td>{{ $item['code'] }}</td>
                                        <td class="font-weight-bold">{{ $item['name'] }}</td>
                                        <td><x-approval_badge :status="$item['approval_status_label']" /></td>
                                        <td>
                                            <small class="{{ $item['approval_reason'] ? 'text-danger' : 'text-muted' }}">
                                                {{ $item['approval_reason'] ?: '-' }}
                                            </small>
                                        </td>
                                        <td style="min-width: 240px;">
                                            <div class="d-flex flex-wrap align-items-center">
                                                @if ($item['edit_url'])
                                                    <a href="{{ $item['edit_url'] }}" class="btn btn-outline-primary btn-sm mr-2 mb-2">
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </a>
                                                @endif

                                                @if ($item['can_approve_admin'] || $item['can_approve_director'])
                                                    <form action="{{ route('import.approvals.item_approve', [$item['type'], $item['id']]) }}" method="POST" class="mr-2 mb-2">
                                                        @csrf
                                                        <input type="hidden" name="batch" value="{{ $batch->id }}">
                                                        <input type="hidden" name="item_page" value="{{ (int) floor(($loop->index) / 5) + 1 }}">
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fas fa-check mr-1"></i>Approve
                                                        </button>
                                                    </form>

                                                @endif

                                                @if ($item['can_reject_admin'] || $item['can_reject_director'])
                                                    <form action="{{ route('import.approvals.item_reject', [$item['type'], $item['id']]) }}" method="POST" class="mb-2 w-100">
                                                        @csrf
                                                        <input type="hidden" name="batch" value="{{ $batch->id }}">
                                                        <input type="hidden" name="item_page" value="{{ (int) floor(($loop->index) / 5) + 1 }}">
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="reason" class="form-control" placeholder="Alasan reject" required>
                                                            <div class="input-group-append">
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-times mr-1"></i>Reject
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endif

                                                @if (!$item['edit_url'] && !$item['can_approve_admin'] && !$item['can_approve_director'] && !$item['can_reject_admin'] && !$item['can_reject_director'])
                                                    <small class="text-muted">Tidak ada aksi yang bisa dilakukan</small>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($items->count() > 5)
                        <div class="d-flex flex-wrap justify-content-between align-items-center px-3 py-3 border-top bg-light js-approval-pagination"
                            data-batch-id="{{ $batch->id }}"
                            data-per-page="5"
                            data-initial-page="{{ request('batch') == $batch->id ? request('item_page', 1) : 1 }}">
                            <div class="d-flex flex-wrap align-items-center">
                                <small class="text-muted js-approval-pagination-info mr-3 mb-2 mb-md-0"></small>
                                <div class="d-flex align-items-center mb-2 mb-md-0">
                                    <label class="small text-muted mb-0 mr-2" for="approvalPerPage{{ $batch->id }}">Per page</label>
                                    <select id="approvalPerPage{{ $batch->id }}" class="form-control form-control-sm js-approval-per-page" style="width: 90px;">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center mt-2 mt-md-0">
                                <button type="button" class="btn btn-outline-secondary js-approval-prev">
                                    <i class="fas fa-chevron-left mr-1"></i>Prev
                                </button>
                                <div class="btn-group btn-group-sm mx-2 js-approval-page-numbers" role="group"></div>
                                <button type="button" class="btn btn-outline-secondary js-approval-next">
                                    Next<i class="fas fa-chevron-right ml-1"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-light border">Tidak ada data import yang perlu diproses pada tahap approval ini.</div>
    @endforelse
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
}

.history-batch-card {
    border-radius: 12px;
    overflow: hidden;
}
</style>

<script>
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
    button.innerHTML = '<i class="fas fa-chevron-down mr-1"></i>Detail';
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.js-approval-pagination').forEach(function(container) {
        const batchId = container.getAttribute('data-batch-id');
        const rows = Array.from(document.querySelectorAll(`.js-approval-row[data-batch-id="${batchId}"]`));
        const info = container.querySelector('.js-approval-pagination-info');
        const prevButton = container.querySelector('.js-approval-prev');
        const nextButton = container.querySelector('.js-approval-next');
        const pageNumbers = container.querySelector('.js-approval-page-numbers');
        const perPageSelect = container.querySelector('.js-approval-per-page');
        let currentPage = parseInt(container.getAttribute('data-initial-page') || '1', 10);
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

                const numberCell = row.querySelector('.js-approval-number');
                if (numberCell) {
                    numberCell.textContent = index + 1;
                }
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

        renderPage(currentPage);
    });
});
</script>
@endsection
