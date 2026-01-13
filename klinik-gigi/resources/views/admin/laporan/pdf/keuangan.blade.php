<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan {{ $year }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            color: #0d6efd;
            font-size: 22px;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
        }

        .summary-table {
            width: 100%;
            margin-bottom: 25px;
        }

        .summary-table td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        .summary-table .label {
            background: #f8f9fa;
            font-weight: bold;
            width: 40%;
        }

        .summary-table .value {
            text-align: right;
            font-size: 14px;
        }

        .success {
            color: #198754;
        }

        .danger {
            color: #dc3545;
        }

        .primary {
            color: #0d6efd;
        }

        .section-title {
            background: #0d6efd;
            color: white;
            padding: 8px 15px;
            margin: 20px 0 10px;
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .data-table th {
            background: #f8f9fa;
            font-weight: bold;
        }

        .data-table .text-right {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <p>Zenith Dental Clinic</p>
        <p>Periode:
            {{ $month ? Carbon\Carbon::create()->month($month)->translatedFormat('F') . ' ' : 'Tahun ' }}{{ $year }}</p>
    </div>

    <div class="section-title">Ringkasan Keuangan</div>
    <table class="summary-table">
        <tr>
            <td class="label">Pendapatan Kotor</td>
            <td class="value success">Rp {{ number_format($pendapatanKotor, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Pengeluaran (Modal Obat)</td>
            <td class="value danger">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Laba Kotor</td>
            <td class="value primary"><strong>Rp {{ number_format($labaKotor, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div class="section-title">Pendapatan per Bulan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            @endphp
            @foreach($pendapatanBulanan as $item)
                <tr>
                    <td>{{ $bulanNames[$item->bulan] ?? $item->bulan }}</td>
                    <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="label"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($pendapatanKotor, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh sistem pada {{ now()->format('d M Y H:i') }}<br>
        &copy; {{ date('Y') }} Zenith Dental Clinic
    </div>
</body>

</html>