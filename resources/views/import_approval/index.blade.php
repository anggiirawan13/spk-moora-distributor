@extends('layouts.app')

@section('title', 'Approval Import')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-check-double text-success mr-2"></i>Approval Import
        </h1>
    </div>

    <x-alert />

    @forelse ($batches as $entry)
        @php
            $batch = $entry['batch'];
            $items = $entry['items'];
            $canApproveBatch = auth()->user()->is_admin == 1
                ? $items->where('can_approve_admin', true)->isNotEmpty()
                : $items->where('can_approve_director', true)->isNotEmpty();
            $batchRoute = auth()->user()->is_admin == 1
                ? route('import.approvals.batch_admin', $batch)
                : route('import.approvals.batch_director', $batch);
        @endphp

        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-gradient-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fas fa-file-import mr-2"></i>Batch #{{ $batch->id }}
                        </h5>
                        <small>
                            File: {{ $batch->original_file_name ?: '-' }} |
                            Importer: {{ $batch->importedBy->name ?? '-' }} |
                            {{ $batch->created_at?->format('d-m-Y H:i') }}
                        </small>
                    </div>
                    @if ($canApproveBatch)
                        <form action="{{ $batchRoute }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm font-weight-bold">
                                <i class="fas fa-check mr-1"></i>Approve Semua Eligible
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Tipe</th>
                                <th>Data</th>
                                <th>Ringkasan</th>
                                <th>Status</th>
                                <th>Alasan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item['type_label'] }}</td>
                                    <td class="font-weight-bold">{{ $item['display_name'] }}</td>
                                    <td>{{ $item['description'] }}</td>
                                    <td><x-approval_badge :status="$item['approval_status_label']" /></td>
                                    <td>
                                        <small class="{{ $item['approval_reason'] ? 'text-danger' : 'text-muted' }}">
                                            {{ $item['approval_reason'] ?: '-' }}
                                        </small>
                                    </td>
                                    <td style="min-width: 260px;">
                                        <div class="d-flex flex-wrap align-items-center">
                                            @if ($item['edit_url'])
                                                <a href="{{ $item['edit_url'] }}" class="btn btn-outline-primary btn-sm mr-2 mb-2">
                                                    <i class="fas fa-edit mr-1"></i>Lihat/Edit
                                                </a>
                                            @endif

                                            @if ($item['can_approve_admin'] || $item['can_approve_director'])
                                                <form action="{{ route('import.approvals.item_approve', [$item['type'], $item['id']]) }}" method="POST" class="mr-2 mb-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check mr-1"></i>Approve
                                                    </button>
                                                </form>

                                                <form action="{{ route('import.approvals.item_reject', [$item['type'], $item['id']]) }}" method="POST" class="mb-2 w-100">
                                                    @csrf
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
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
</style>
@endsection
