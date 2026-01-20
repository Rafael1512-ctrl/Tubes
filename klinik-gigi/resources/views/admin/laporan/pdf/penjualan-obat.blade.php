<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Obat {{ $year }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #11998e;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            color: #11998e;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
        }

        .summary-box {
            background: #e8f8f5;
            border: 1px solid #11998e;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .summary-box h2 {
            margin: 0;
            color: #11998e;
            font-size: 24px;
        }

        .summary-box p {
            margin: 5px 0 0;
            color: #666;
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

        .data-table .text-center {
            text-align: center;
        }

        .data-table tfoot td {
            background: #f8f9fa;
            font-weight: bold;
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
        <h1>LAPORAN PENJUALAN OBAT</h1>
        <p>Zenith Dental Clinic</p>
        <p>Periode:
            {{ $month ? Carbon\Carbon::create()->month((int)$month)->translatedFormat('F') . ' ' : 'Tahun ' }}{{ $year }}</p>
    </div>

    <div class="summary-box">
        <p>Total Penjualan Obat</p>
        <h2>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h2>
        <p>{{ $penjualan->count() }} jenis obat terjual</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Satuan</th>
                <th class="text-center">Total Terjual</th>
                <th class="text-right">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->NamaObat }}</td>
                    <td>{{ $item->Satuan }}</td>
                    <td class="text-center">{{ $item->TotalJumlah }}</td>
                    <td class="text-right">Rp {{ number_format($item->TotalPenjualan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">TOTAL</td>
                <td class="text-center">{{ $penjualan->sum('TotalJumlah') }}</td>
                <td class="text-right">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh sistem pada {{ now()->format('d M Y H:i') }}<br>
        &copy; {{ date('Y') }} Zenith Dental Clinic
    </div>
</body>

</html>