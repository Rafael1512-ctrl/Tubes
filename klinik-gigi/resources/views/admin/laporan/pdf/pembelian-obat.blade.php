<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pembelian Obat {{ $year }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #f5576c;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            color: #f5576c;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
        }

        .summary-box {
            background: #fff3f5;
            border: 1px solid #f5576c;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .summary-box h2 {
            margin: 0;
            color: #f5576c;
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
            padding: 6px 8px;
            text-align: left;
        }

        .data-table th {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 10px;
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
        <h1>LAPORAN PEMBELIAN OBAT</h1>
        <p>Zenith Dental Clinic</p>
        <p>Periode:
            {{ $month ? Carbon\Carbon::create()->month((int)$month)->translatedFormat('F') . ' ' : 'Tahun ' }}{{ $year }}
        </p>
    </div>

    <div class="summary-box">
        <p>Total Pembelian</p>
        <h2>Rp {{ number_format($totalPembelian, 0, ',', '.') }}</h2>
        <p>{{ $pembelian->count() }} transaksi | {{ $pembelian->sum('Jumlah') }} unit obat</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Obat</th>
                <th class="text-center">Jumlah</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Subtotal</th>
                <th>Oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ Carbon\Carbon::parse($item->Tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->NamaObat }}</td>
                    <td class="text-center">{{ $item->Jumlah }} {{ $item->Satuan }}</td>
                    <td class="text-right">Rp {{ number_format($item->HargaBeli, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->Subtotal, 0, ',', '.') }}</td>
                    <td>{{ $item->CreatedBy }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">TOTAL</td>
                <td class="text-right">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh sistem pada {{ now()->format('d M Y H:i') }}<br>
        &copy; {{ date('Y') }} Zenith Dental Clinic
    </div>
</body>

</html>