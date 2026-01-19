<x-alert />

    <div class="card shadow mb-4 border-0">
        <div class="card-header text-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="text-primary m-0 font-weight-bold">
                <i class="fas fa-table mr-2"></i>{{ $title }}
            </h5>
            @auth
                @if (auth()->user()->is_admin == 1)
                    <a href="{{ route($createRoute) }}" id="btn-add-data" class="btn btn-primary btn-sm text-white">
                        <i class="fas fa-plus-circle mr-1"></i>Tambah Data
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="card-body shadow mb-4 border-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped" width="100%" id="dataTable" cellspacing="0">
                <thead class="bg-gradient-primary text-white">
                    <tr>
                        <th class="text-center" style="width: 60px;">No.</th>
                        @foreach ($columns as $column)
                            <th class="font-weight-bold text-center">{{ $column['label'] }}</th>
                        @endforeach
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $key => $item)
                        <tr class="transition-all">
                            <td class="text-center text-muted">{{ $key + 1 }}</td>
                            @foreach ($columns as $column)
                                <td>
                                    @if ($column['field'] === 'is_admin')
                                        <span class="badge badge-pill {{ $item['is_admin'] ? 'badge-danger' : 'badge-success' }}">
                                            <i class="fas {{ $item['is_admin'] === 1 ? 'fa-user-shield' : 'fa-user' }} mr-1"></i>
                                            {{ $item['is_admin'] ? 'Admin' : 'Staf' }}
                                        </span>
                                    @elseif ($column['field'] === 'is_active' || $column['field'] === 'is_available')
                                        <span class="badge badge-pill {{ $item[$column['field']] ? 'badge-success' : 'badge-secondary' }}">
                                            <i class="fas {{ $item[$column['field']] ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                            {{ $item[$column['field']] ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    @elseif (in_array($column['field'], ['created_at', 'updated_at']))
                                        <span class="text-muted small">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($item[$column['field']])->format('d M Y H:i') }}
                                        </span>
                                    @elseif (strpos($column['field'], 'score') !== false)
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 mr-2" style="height: 6px;">
                                                <div class="progress-bar bg-success" 
                                                     role="progressbar" 
                                                     style="width: {{ $item[$column['field']] }}%"
                                                     aria-valuenow="{{ $item[$column['field']] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="badge badge-light">{{ $item[$column['field']] }}</span>
                                        </div>
                                    @else
                                        @if (!empty($column['html']) && $column['html'])
                                            {!! $item[$column['field']] !!}
                                        @elseif (!empty($column['php']) && $column['php'])
                                            {{ $column['field'] }}
                                        @else
                                            {{ $item[$column['field']] ?? '-' }}
                                        @endif
                                    @endif
                                </td>
                            @endforeach
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route($showRoute, $item['id']) }}" 
                                       class="btn btn-info btn-sm btn-action m-1"
                                       data-toggle="tooltip" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @auth
                                        @if (auth()->user()->is_admin == 1)
                                            <a href="{{ route($editRoute, $item['id']) }}" 
                                               class="btn btn-primary btn-sm btn- m-1"
                                               data-toggle="tooltip" title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <button type="button" 
                                                    class="btn btn-danger btn-sm btn-action m-1"
                                                    onclick="confirmDelete('{{ route($deleteRoute, $item['id']) }}', '{{ $item[$columns[0]['field']] ?? $item['name'] ?? 'Data' }}')"
                                                    data-toggle="tooltip" title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 2 }}" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p class="mb-0">Tidak ada data ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete(url, name) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus <strong>"${name}"</strong>?`,
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
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        
        $('.btn-action').hover(
            function() {
                $(this).css('transform', 'scale(1.1)');
            },
            function() {
                $(this).css('transform', 'scale(1)');
            }
        );
    });
</script>

<style>
.table thead th {
    border-bottom: 2px solid #059669;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table-hover tbody tr:hover {
    background-color: rgba(5, 150, 105, 0.05);
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.02);
}

.btn-action {
    border-radius: 6px;
    padding: 0.375rem 0.75rem;
    transition: all 0.2s ease;
    border: none;
}

.badge-pill {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.progress {
    background-color: #e9ecef;
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

.card {
    border-radius: 10px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
}

.bg-gradient-primary {
    background: #059669 !important;
}

.transition-all {
    transition: all 0.3s ease;
}
</style>