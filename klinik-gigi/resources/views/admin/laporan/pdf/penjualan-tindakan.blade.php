<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Tindakan {{ $year }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #3494E6;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            color: #3494E6;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
        }

        .summary-box {
            background: #e8f4fd;
            border: 1px solid #3494E6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .summary-box h2 {
            margin: 0;
            color: #3494E6;
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

        .category {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
        }

        .cat-pemeriksaan {
            background: #cff4fc;
            color: #055160;
        }

        .cat-perawatan {
            background: #d1e7dd;
            color: #0a3622;
        }

        .cat-bedah {
            background: #f8d7da;
            color: #58151c;
        }

        .cat-estetik {
            background: #fff3cd;
            color: #664d03;
        }

        .cat-umum {
            background: #e2e3e5;
            color: #41464b;
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
        <h1>LAPORAN PENJUALAN TINDAKAN</h1>
        <p>Zenith Dental Clinic</p>
        <p>Periode:
            {{ $month ? Carbon\Carbon::create()->month($month)->translatedFormat('F') . ' ' : 'Tahun ' }}{{ $year }}</p>
    </div>

    <div class="summary-box">
        <p>Total Pendapatan Tindakan</p>
        <h2>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        <p>{{ $penjualan->sum('JumlahTindakan') }} tindakan dari {{ $penjualan->count() }} jenis layanan</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Tindakan</th>
                <th>Kategori</th>
                <th class="text-center">Jumlah</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $index => $item)
                @php
                    $catClass = [
                        'Pemeriksaan' => 'cat-pemeriksaan',
                        'Perawatan' => 'cat-perawatan',
                        'Bedah' => 'cat-bedah',
                        'Estetik' => 'cat-estetik',
                    ][$item->Kategori] ?? 'cat-umum';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->NamaTindakan }}</td>
                    <td><span class="category {{ $catClass }}">{{ $item->Kategori ?? 'Umum' }}</span></td>
                    <td class="text-center">{{ $item->JumlahTindakan }}x</td>
                    <td class="text-right">Rp {{ number_format($item->TotalPendapatan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">TOTAL</td>
                <td class="text-center">{{ $penjualan->sum('JumlahTindakan') }}x</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh sistem pada {{ now()->format('d M Y H:i') }}<br>
        &copy; {{ date('Y') }} Zenith Dental Clinic
    </div>
</body>

</html>