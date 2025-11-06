<!DOCTYPE html>
<html>
<head>
    <title>Laporan MOORA</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .product-info { background: #f8f9fa; padding: 15px; margin-bottom: 20px; border-left: 4px solid #007bff; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .table th { background-color: #f2f2f2; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .bg-primary { background-color: #007bff; color: white; }
        .ranking-1 { background-color: #d4edda; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Hasil Perhitungan MOORA</h1>
        <p>Sistem Pendukung Keputusan Rekomendasi Distributor Barang Elektrikal</p>
    </div>

    @if(isset($product) && $product)
    <div class="product-info">
        <h3>Produk: {{ $product->name }}</h3>
        @if($product->description)
        <p>Deskripsi: {{ $product->description }}</p>
        @endif
        <p>Jumlah Distributor: {{ $alternatives->count() }}</p>
    </div>
    @else
    <div class="product-info">
        <h3>Semua Distributor</h3>
        <p>Analisis semua distributor yang tersedia dalam sistem</p>
        <p>Jumlah Distributor: {{ $alternatives->count() }}</p>
    </div>
    @endif

    <!-- Summary Information -->
    <div style="margin-bottom: 20px;">
        <h3>Ringkasan Hasil</h3>
        <p>Total Alternatif: {{ $alternatives->count() }}</p>
        <p>Total Kriteria: {{ $criteria->count() }}</p>
        <p>Distributor Terbaik: 
            @php
                $topAlt = $alternatives->firstWhere('id', array_key_first($valueMoora));
            @endphp
            {{ optional($topAlt->distributor)->name ?? 'N/A' }}
        </p>
        <p>Nilai Tertinggi: {{ number_format(max($valueMoora), 5) }}</p>
    </div>

    <!-- Step 1: Data Awal -->
    <h3>Step 1: Data Alternatif Awal</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Alternatif</th>
                @foreach ($criteria as $c)
                    <th>{{ $c->name }} ({{ $c->code }})</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($alternatives as $a)
                <tr>
                    <td style="text-align: left;">{{ optional($a->distributor)->name ?? ($a->name ?? '—') }}</td>
                    @foreach ($criteria as $c)
                        @php
                            $val = $altValues[$a->id][$c->id] ?? 0;
                        @endphp
                        <td class="{{ $val > 0 ? 'text-success' : '' }}">
                            {{ number_format($val, 5) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Step 5 & 6: Hasil Akhir MOORA & Ranking -->
    <h3>Hasil Akhir MOORA & Peringkat</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Alternatif</th>
                <th>Total Benefit</th>
                <th>Total Cost</th>
                <th>Yi (Benefit - Cost)</th>
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

                <tr class="{{ $rank === 1 ? 'ranking-1' : '' }}">
                    <td>
                        @if($rank === 1)
                            <strong>#{{ $rank }} 🏆</strong>
                        @else
                            #{{ $rank }}
                        @endif
                    </td>
                    <td style="text-align: left;">{{ optional($alt->distributor)->name ?? ($alt->name ?? '—') }}</td>
                    <td class="text-success">{{ number_format($benefit, 5) }}</td>
                    <td class="text-danger">{{ number_format($cost, 5) }}</td>
                    <td><strong>{{ number_format($yi, 5) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        <p>Dibuat pada: {{ now()->format('d F Y H:i') }}</p>
        <p>Sistem Pendukung Keputusan - Metode MOORA</p>
    </div>
</body>
</html>