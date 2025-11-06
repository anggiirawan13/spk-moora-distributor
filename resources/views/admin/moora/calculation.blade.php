@extends('layouts.app')

@section('title', 'Hasil Perhitungan MOORA')

@section('content')

    @php
        $getValue = function ($alternative, $criteriaId) {
            foreach ($alternative->values as $val) {
                if ($val->subCriteria && $val->subCriteria->criteria_id === $criteriaId) {
                    return $val->subCriteria->value;
                }
            }
            return 0;
        };
    @endphp

    <div class="container-fluid">

        <!-- Header Section -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calculator text-primary mr-2"></i>Hasil Perhitungan MOORA
            </h1>
            <a href="{{ route('admin.moora.download_pdf') }}" class="btn btn-success btn-lg">
                <i class="fas fa-download mr-2"></i>Download Laporan PDF
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Alternatif</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $alternatives->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-th fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Kriteria</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $criteria->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Rekomendasi Distributor</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800 text-truncate">
                                    @php
                                        $topAlt = $alternatives->firstWhere('id', array_key_first($valueMoora));
                                    @endphp
                                    {{ optional($topAlt->distributor)->name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-trophy fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Nilai Tertinggi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format(max($valueMoora), 5) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 1: Masukkan Data Alternatif --}}
        <div class="card shadow mb-4 border-0">
            <div class="card-header bg-gradient-primary text-white py-3">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-database mr-2"></i>Step 1: Data Alternatif Awal
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Rumus kuadrat per kriteria:</strong> 
                    <code>∑(x<sub>ij</sub>)² = x<sub>1j</sub>² + x<sub>2j</sub>² + ... + x<sub>nj</sub>²</code>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="align-middle">Alternatif</th>
                                @foreach ($criteria as $c)
                                    <th class="align-middle">
                                        <div>{{ $c->name }}</div>
                                        <small class="text-muted">({{ $c->code }})</small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alternatives as $a)
                                <tr>
                                    <td class="font-weight-bold text-left">
                                        <i class="fas fa-warehouse text-primary mr-2"></i>
                                        {{ optional($a->distributor)->name ?? ($a->name ?? '—') }}
                                    </td>
                                    @foreach ($criteria as $c)
                                        @php
                                            $val = $getValue($a, $c->id);
                                        @endphp
                                        <td class="{{ $val > 0 ? 'text-success font-weight-bold' : 'text-muted' }}">
                                            {{ number_format($val, 5) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gradient-light font-weight-bold">
                            <tr>
                                <td>∑(x<sub>ij</sub>)²</td>
                                @foreach ($criteria as $c)
                                    @php
                                        $sumSquare = $alternatives->sum(function ($a) use ($c, $getValue) {
                                            $v = $getValue($a, $c->id);
                                            return pow($v, 2);
                                        });
                                    @endphp
                                    <td class="text-primary">{{ number_format($sumSquare, 5) }}</td>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Step 2: Normalisasi Akar --}}
        <div class="card shadow mb-4 border-0">
            <div class="card-header bg-gradient-success text-white py-3">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-square-root-alt mr-2"></i>Step 2: Perhitungan Akar untuk Normalisasi
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Rumus akar untuk normalisasi:</strong> 
                    <code>√(∑(x<sub>ij</sub>)²) = √(x<sub>1j</sub>² + x<sub>2j</sub>² + ... + x<sub>nj</sub>²)</code>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-light">
                            <tr>
                                @foreach ($criteria as $c)
                                    <th class="align-middle">
                                        <div>{{ $c->name }}</div>
                                        <small class="text-muted">({{ $c->code }})</small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-light">
                                @foreach ($criteria as $c)
                                    @php
                                        $sumSquares = $alternatives->sum(function ($a) use ($c, $getValue) {
                                            $val = $getValue($a, $c->id);
                                            return pow($val, 2);
                                        });
                                    @endphp
                                    <td>
                                        <div class="text-muted small">∑(x<sub>ij</sub>)²</div>
                                        <div class="font-weight-bold">{{ number_format($sumSquares, 5) }}</div>
                                    </td>
                                @endforeach
                            </tr>

                            <tr class="bg-gradient-light font-weight-bold">
                                @foreach ($criteria as $c)
                                    <td class="text-success">
                                        <div class="small">√(∑(x<sub>ij</sub>)²)</div>
                                        {{ number_format($normDivisor[$c->id], 5) }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Step 3: Normalisasi Nilai Alternatif --}}
        <div class="card shadow mb-4 border-0">
            <div class="card-header bg-gradient-info text-white py-3">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-calculator mr-2"></i>Step 3: Normalisasi Nilai Alternatif
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Rumus normalisasi:</strong> 
                    <code>r<sub>ij</sub> = x<sub>ij</sub> / √(∑x<sub>ij</sub>²)</code>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="align-middle">Alternatif</th>
                                @foreach ($criteria as $c)
                                    <th class="align-middle">
                                        <div>{{ $c->name }}</div>
                                        <small class="text-muted">({{ $c->code }})</small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alternatives as $a)
                                <tr>
                                    <td class="font-weight-bold text-left">
                                        <i class="fas fa-warehouse text-primary mr-2"></i>
                                        {{ optional($a->distributor)->name ?? ($a->name ?? '—') }}
                                    </td>
                                    @foreach ($criteria as $c)
                                        @php
                                            $raw = $getValue($a, $c->id);
                                            $norm = $raw / ($normDivisor[$c->id] ?: 1);
                                        @endphp
                                        <td class="{{ $norm > 0 ? 'text-success' : 'text-muted' }}">
                                            {{ number_format($norm, 5) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Step 4: Nilai Normalisasi x Bobot --}}
        <div class="card shadow mb-4 border-0">
            <div class="card-header bg-gradient-warning text-white py-3">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-weight-hanging mr-2"></i>Step 4: Nilai Normalisasi × Bobot Kriteria
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Rumus nilai tertimbang:</strong> 
                    <code>y<sub>ij</sub> = r<sub>ij</sub> × w<sub>j</sub></code>
                    <br>
                    <small class="mt-1">
                        <code>r<sub>ij</sub></code>: nilai normalisasi • 
                        <code>w<sub>j</sub></code>: bobot kriteria
                    </small>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="align-middle">Alternatif</th>
                                @foreach ($criteria as $c)
                                    <th class="align-middle">
                                        <div>{{ $c->name }}</div>
                                        <small class="text-muted">
                                            {{ $c->code }} (w={{ $c->weight }})
                                        </small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alternatives as $a)
                                <tr>
                                    <td class="font-weight-bold text-left">
                                        <i class="fas fa-warehouse text-primary mr-2"></i>
                                        {{ optional($a->distributor)->name ?? ($a->name ?? '—') }}
                                    </td>
                                    @foreach ($criteria as $c)
                                        <td class="{{ ($normalization[$a->id][$c->id] ?? 0) > 0 ? 'text-success font-weight-bold' : 'text-muted' }}">
                                            {{ number_format($normalization[$a->id][$c->id] ?? 0, 5) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Step 5 & 6: Hitung MOORA dan Ranking --}}
        <div class="card shadow mb-4 border-0">
            <div class="card-header bg-gradient-danger text-white py-3">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-trophy mr-2"></i>Step 5-6: Hasil Akhir MOORA & Peringkat
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Rumus nilai akhir MOORA:</strong> 
                    <code>Yi = Σ(W<sub>j</sub> × r<sub>ij</sub>) (benefit) − Σ(W<sub>j</sub> × r<sub>ij</sub>) (cost)</code>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="align-middle">Peringkat</th>
                                <th class="align-middle">Alternatif</th>
                                <th class="align-middle">Total Benefit</th>
                                <th class="align-middle">Total Cost</th>
                                <th class="align-middle">Yi (Benefit - Cost)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rank = 0;
                                $prevYi = null;
                            @endphp

                            @foreach ($valueMoora as $id => $yi)
                                @php
                                    if ($yi !== $prevYi) {
                                        $rank++;
                                        $prevYi = $yi;
                                    }

                                    $alt = $alternatives->firstWhere('id', $id);
                                    $benefit = $cost = 0;
                                    foreach ($criteria as $c) {
                                        $value = $normalization[$id][$c->id] ?? 0;
                                        if (strtolower($c->attribute_type) === 'benefit') {
                                            $benefit += $value;
                                        } else {
                                            $cost += $value;
                                        }
                                    }
                                @endphp

                                <tr class="{{ $rank === 1 ? 'table-success font-weight-bold' : '' }}">
                                    <td>
                                        @if($rank === 1)
                                            <span class="badge badge-success badge-pill px-3 py-2">
                                                <i class="fas fa-crown mr-1"></i>{{ $rank }}
                                            </span>
                                        @else
                                            <span class="badge badge-primary badge-pill">{{ $rank }}</span>
                                        @endif
                                    </td>
                                    <td class="text-left">
                                        <i class="fas fa-warehouse text-primary mr-2"></i>
                                        {{ optional($alt->distributor)->name ?? ($alt->name ?? '—') }}
                                    </td>
                                    <td class="text-success font-weight-bold">{{ number_format($benefit, 5) }}</td>
                                    <td class="text-danger font-weight-bold">{{ number_format($cost, 5) }}</td>
                                    <td class="font-weight-bold text-primary">{{ number_format($yi, 5) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <style>
    .bg-gradient-primary { background: linear-gradient(135deg, #047857 0%, #059669 100%) !important; }
    .bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
    .bg-gradient-info { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%) !important; }
    .bg-gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; }
    .bg-gradient-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important; }
    .bg-gradient-light { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important; }
    
    .card { border-radius: 12px; overflow: hidden; }
    .table { border-radius: 8px; overflow: hidden; }
    .badge-pill { border-radius: 50rem; }
    
    .table-hover tbody tr:hover {
        background-color: rgba(5, 150, 105, 0.05);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    </style>

@endsection