<!DOCTYPE html>
<html>
<head>
    <title>Laporan Tahunan Clinic Zenith - {{ $year }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #2b3a67; }
        .summary-box { width: 100%; margin-bottom: 20px; }
        .summary-item { width: 33.33%; float: left; text-align: center; }
        .summary-card { background: #f8f9fa; padding: 15px; margin: 5px; border-radius: 8px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #dee2e6; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #666; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="header">
        <h2>ZENITH DENTAL CLINIC</h2>
        <p>Laporan Performa Klinik - Tahun {{ $year }}</p>
    </div>

    <div class="summary-box clearfix">
        <div class="summary-item">
            <div class="summary-card">
                <small>Total Pendapatan</small>
                <h3>Rp {{ number_format($totalRevenueYear, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="summary-item">
            <div class="summary-card">
                <small>Pasien Baru</small>
                <h3>{{ $totalPasienNew }}</h3>
            </div>
        </div>
        <div class="summary-item">
            <div class="summary-card">
                <small>Total Pemeriksaan</small>
                <h3>{{ $totalPemeriksaan }}</h3>
            </div>
        </div>
    </div>

    <h4>Rincian Pendapatan Bulanan</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $monthlyMap = $revenueData->pluck('total', 'month')->all();
            @endphp
            @foreach($months as $index => $name)
            <tr>
                <td>{{ $name }}</td>
                <td class="text-right">Rp {{ number_format($monthlyMap[$index+1] ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="margin-top: 30px;">Top 10 Layanan Terpopuler</h4>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Layanan</th>
                <th class="text-right">Frekuensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($popularTindakan as $index => $t)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $t->NamaTindakan }}</td>
                <td class="text-right">{{ $t->total }}x</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->isoFormat('D MMMM YYYY HH:mm') }} | Zenith Dental Clinic System
    </div>
</body>
</html>
