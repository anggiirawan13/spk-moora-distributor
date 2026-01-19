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

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Perhitungan MOORA</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #2d3748;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        .header {
            border-bottom: 2px solid #047857;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 18px;
            color: #047857;
            margin: 0;
            font-weight: bold;
        }

        .subtitle {
            font-size: 12px;
            color: #6b7280;
            margin: 5px 0 0 0;
        }

        .meta-info {
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            font-size: 9px;
        }

        .meta-row {
            display: flex;
            margin-bottom: 4px;
        }

        .meta-label {
            font-weight: bold;
            color: #374151;
            min-width: 80px;
        }

        .meta-value {
            color: #6b7280;
        }

        #assessment-criteria {
            margin-top: 5px;
        }

        .section-title {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            color: black;
            font-size: 11px;
            font-weight: bold;
            margin: 25px 0 12px 0;
            border-radius: 4px;
            page-break-inside: avoid;
        }

        .formula {
            background: #f3f4f6;
            border-left: 3px solid #047857;
            padding: 6px 10px;
            margin: 8px 0 15px 0;
            font-size: 9px;
            color: #4b5563;
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 20px;
            font-size: 9px;
            page-break-inside: avoid;
        }

        th {
            background: #047857;
            color: white;
            font-weight: bold;
            padding: 6px 4px;
            border: 1px solid #059669;
        }

        td {
            border: 1px solid #d1d5db;
            padding: 5px 4px;
            text-align: center;
        }

        tfoot td {
            background: #f9fafb;
            font-weight: bold;
            border-top: 2px solid #9ca3af;
        }

        .alternatif-name {
            text-align: left;
            font-weight: bold;
            color: #1f2937;
        }

        .number-cell {
            font-family: 'DejaVu Sans Mono', monospace;
            color: #374151;
        }

        .highlight-row {
            background: #f0fdf4 !important;
        }

        .rank-1 {
            background: #dcfce7 !important;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 8px;
            color: #6b7280;
            text-align: center;
        }

        .criteria-info {
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 8px;
            margin: 10px 0;
            font-size: 8px;
        }

        .criteria-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            margin-right: 5px;
        }

        .badge-benefit {
            background: #10b981;
            color: white;
        }

        .badge-cost {
            background: #ef4444;
            color: white;
        }

        .summary-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 0px 10px;
            margin: 15px 0;
            font-size: 9px;
        }

        .summary-title {
            font-weight: bold;
            color: #047857;
            margin-bottom: 5px;
        }

        .page-break {
            page-break-before: always;
        }

        /* Zebra striping for tables */
        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        /* Header for multi-page tables */
        thead {
            display: table-header-group;
        }

        /* Ensure tables break properly across pages */
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-main">
            <div>
                <h1>LAPORAN PERHITUNGAN METODE MOORA</h1>
                <p class="subtitle">Sistem Pendukung Keputusan Rekomendasi Distributor Barang Elektrikal</p>
            </div>
            <div class="meta-info">
                <div class="meta-row">
                    <span class="meta-label">Pengguna:</span>
                    <span class="meta-value">{{ auth()->user()->name }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Tanggal:</span>
                    <span class="meta-value">{{ \Carbon\Carbon::now()->format('d F Y') }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Waktu:</span>
                    <span class="meta-value">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="criteria-info">
        <strong>Kriteria Penilaian:</strong>
        <div id="assessment-criteria">
            @foreach ($criteria as $c)
                <span class="criteria-badge {{ strtolower($c->attribute_type) === 'benefit' ? 'badge-benefit' : 'badge-cost' }}">
                    {{ $c->code }}: {{ $c->name }} ({{ $c->weight }})
                </span>
            @endforeach
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-title">📊 Ringkasan Analisis</div>
        <div>Product: <strong>{{ $productSelected->name ?? 'Tidak ada produk yang dipilih' }}</strong></div>
        <div>Total Alternatif: <strong>{{ count($alternatives) }}</strong></div>
        <div>Total Kriteria: <strong>{{ count($criteria) }}</strong></div>
        <div>Metode: <strong>MOORA (Multi-Objective Optimization by Ratio Analysis)</strong></div>
    </div>

    @php
        $bestAltId = array_key_first($valueMoora);
        $bestAlt = $alternatives->firstWhere('id', $bestAltId);
    @endphp
    <div class="summary-box" style="background: #dcfce7; border-color: #bbf7d0;">
        <div class="summary-title">🎯 HASIL REKOMENDASI</div>
        <div>Rekomendasi Alternatif: <strong>{{ optional($bestAlt->distributor)->name ?? ($bestAlt->name ?? '—') }}</strong></div>
        <div>Nilai Akhir (Y<sub>i</sub>): <strong>{{ number_format($valueMoora[$bestAltId], 5) }}</strong></div>
        <div>Peringkat: <strong>1 dari {{ count($alternatives) }} alternatif</strong></div>
    </div>

    {{-- Step 1: Nilai Alternatif --}}
    <div class="section-title">📈 STEP 1: NILAI ALTERNATIF & KUADRAT</div>
    <div class="formula">
        <strong>Rumus:</strong> ∑(x<sub>ij</sub>)² = x<sub>1j</sub>² + x<sub>2j</sub>² + ... + x<sub>nj</sub>²
    </div>
    <table>
        <thead>
            <tr>
                <th width="20%">Alternatif</th>
                @foreach ($criteria as $c)
                    <th>{{ $c->name }}<br><small>({{ $c->code }})</small></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($alternatives as $index => $a)
                <tr class="{{ $index % 2 === 0 ? '' : 'highlight-row' }}">
                    <td class="alternatif-name">{{ optional($a->distributor)->name ?? $a->name }}</td>
                    @foreach ($criteria as $c)
                        @php
                            $val = $getValue($a, $c->id);
                        @endphp
                        <td class="number-cell">{{ number_format($val, 3) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><strong>∑x<sub>ij</sub>²</strong></td>
                @foreach ($criteria as $c)
                    @php
                        $sumSquare = $alternatives->sum(function ($a) use ($c, $getValue) {
                            $v = $getValue($a, $c->id);
                            return pow($v, 2);
                        });
                    @endphp
                    <td class="number-cell"><strong>{{ number_format($sumSquare, 5) }}</strong></td>
                @endforeach
            </tr>
        </tfoot>
    </table>

    {{-- Step 2: Akar Normalisasi --}}
    <div class="section-title">🧮 STEP 2: AKAR TOTAL TIAP KRITERIA</div>
    <div class="formula">
        <strong>Rumus:</strong> √(∑x<sub>ij</sub>²)
    </div>
    <table>
        <thead>
            <tr>
                @foreach ($criteria as $c)
                    <th>{{ $c->name }}<br><small>({{ $c->code }})</small></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach ($criteria as $c)
                    <td class="number-cell"><strong>{{ number_format($normDivisor[$c->id], 5) }}</strong></td>
                @endforeach
            </tr>
        </tbody>
    </table>

    {{-- Step 3: Normalisasi --}}
    <div class="section-title">📊 STEP 3: NORMALISASI NILAI ALTERNATIF</div>
    <div class="formula">
        <strong>Rumus:</strong> r<sub>ij</sub> = x<sub>ij</sub> / √(∑x<sub>ij</sub>²)
    </div>
    <table>
        <thead>
            <tr>
                <th width="20%">Alternatif</th>
                @foreach ($criteria as $c)
                    <th>{{ $c->name }}<br><small>({{ $c->code }})</small></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($alternatives as $index => $a)
                <tr class="{{ $index % 2 === 0 ? '' : 'highlight-row' }}">
                    <td class="alternatif-name">{{ optional($a->distributor)->name ?? $a->name }}</td>
                    @foreach ($criteria as $c)
                        @php
                            $raw = $getValue($a, $c->id);
                            $norm = $raw / ($normDivisor[$c->id] ?: 1);
                        @endphp
                        <td class="number-cell">{{ number_format($norm, 5) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Step 4: Dikali Bobot --}}
    <div class="section-title">⚖️ STEP 4: NILAI NORMALISASI × BOBOT</div>
    <div class="formula">
        <strong>Rumus:</strong> y<sub>ij</sub> = r<sub>ij</sub> × w<sub>j</sub>
    </div>
    <table>
        <thead>
            <tr>
                <th width="20%">Alternatif</th>
                @foreach ($criteria as $c)
                    <th>{{ $c->name }}<br><small>({{ $c->code }}) - {{ $c->weight }}</small></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($alternatives as $index => $a)
                <tr class="{{ $index % 2 === 0 ? '' : 'highlight-row' }}">
                    <td class="alternatif-name">{{ optional($a->distributor)->name ?? $a->name }}</td>
                    @foreach ($criteria as $c)
                        <td class="number-cell">{{ number_format($normalization[$a->id][$c->id] ?? 0, 5) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Step 5 & 6: MOORA --}}
    <div class="section-title">🏆 STEP 5–6: NILAI AKHIR Y<sub>i</sub> DAN PERINGKAT</div>
    <div class="formula">
        <strong>Rumus:</strong> Y<sub>i</sub> = Σ(W<sub>j</sub> × r<sub>ij</sub>) benefit − Σ(W<sub>j</sub> × r<sub>ij</sub>) cost
    </div>
    <table>
        <thead>
            <tr>
                <th width="8%">Peringkat</th>
                <th width="25%">Alternatif</th>
                <th width="17%">Total Benefit</th>
                <th width="17%">Total Cost</th>
                <th width="17%">Nilai Y<sub>i</sub></th>
                <th width="16%">Keterangan</th>
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

                <tr class="{{ $rank === 1 ? 'rank-1' : '' }}">
                    <td><strong>{{ $rank }}</strong></td>
                    <td class="alternatif-name">{{ optional($alt->distributor)->name ?? ($alt->name ?? '—') }}</td>
                    <td class="number-cell">{{ number_format($benefit, 5) }}</td>
                    <td class="number-cell">{{ number_format($cost, 5) }}</td>
                    <td class="number-cell"><strong>{{ number_format($yi, 5) }}</strong></td>
                    <td>
                        @if($rank === 1)
                            <strong>REKOMENDASI</strong>
                        @else
                            <span style="color: #059669;">Alternatif</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh Sistem Pendukung Keputusan<br>
        Dicetak oleh {{ auth()->user()->name }} pada {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}<br>
        <em>Halaman 1 dari 1</em>
    </div>

</body>

</html>