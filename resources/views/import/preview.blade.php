@extends('layouts.app')

@section('title', 'Preview Import Excel')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-search text-primary mr-2"></i>Preview Import Excel
        </h1>
        <a href="{{ route('import.excel.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0 font-weight-bold">
                <i class="fas fa-chart-bar mr-2"></i>Ringkasan Preview
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Sheet</th>
                            <th>Data Baru</th>
                            <th>Di-skip (Error/Duplikat)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stats as $sheet => $stat)
                            <tr>
                                <td class="font-weight-bold">{{ $sheet }}</td>
                                <td>{{ $stat['would_create'] ?? 0 }}</td>
                                <td>{{ $stat['skipped'] ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (!empty($samples))
                <div class="mt-4">
                    <h6 class="font-weight-bold">Preview Data (maks 5 baris per sheet)</h6>
                    @foreach ($samples as $sheet => $rows)
                        <div class="mt-3">
                            <div class="font-weight-bold text-primary mb-2">{{ $sheet }}</div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            @foreach (array_keys($rows[0]) as $header)
                                                <th>{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows as $row)
                                            <tr>
                                                @foreach ($row as $value)
                                                    <td>{{ $value }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (!empty($errors))
                <div class="alert alert-warning mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Ditemukan error. Silakan cek daftar error di bawah.
                        </div>
                        @if ($error_file)
                            <a class="btn btn-sm btn-warning"
                               href="{{ route('import.excel.errors', basename($error_file)) }}">
                                <i class="fas fa-download mr-1"></i>Download Error
                            </a>
                        @endif
                    </div>
                </div>

                <div class="mt-3">
                    <h6 class="font-weight-bold">Ringkasan Error per Sheet</h6>
                    <ul class="list-unstyled mb-3">
                        @foreach ($error_counts as $sheet => $count)
                            <li><strong>{{ $sheet }}</strong>: {{ $count }} error</li>
                        @endforeach
                    </ul>

                    <h6 class="font-weight-bold">Daftar Error ({{ count($errors) }} baris)</h6>
                    <pre class="bg-light p-3 border rounded small" style="max-height: 300px; overflow: auto;">
@foreach ($errors as $error)
{{ $error }}
@endforeach
                    </pre>
                </div>
            @endif

            <form action="{{ route('import.excel.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="stored_file" value="{{ $stored_file }}">
                @if ($error_file)
                    <input type="hidden" name="error_file" value="{{ $error_file }}">
                @endif
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-cloud-upload-alt mr-2"></i>Commit Import
                </button>
            </form>

            @if (!empty($preview_json))
                <div class="mt-4">
                    <h6 class="font-weight-bold">Preview JSON</h6>
                    <button class="btn btn-outline-secondary btn-sm mb-2" type="button" onclick="downloadPreviewJson()">
                        <i class="fas fa-download mr-1"></i>Download JSON
                    </button>
                    <pre id="previewJson" class="bg-light p-3 border rounded small" style="max-height: 300px; overflow: auto;">{{ $preview_json }}</pre>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #047857 0%, #059669 100%) !important;
}
</style>

@if (!empty($preview_json))
<script>
function downloadPreviewJson() {
    const content = document.getElementById('previewJson').textContent;
    const blob = new Blob([content], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'import-preview.json';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
@endif
@endsection
