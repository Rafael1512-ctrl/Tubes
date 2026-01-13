@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Laporan Pembelian Obat')
@section('header-title', 'Laporan Pembelian Obat')
@section('header-subtitle', 'Data pembelian stok obat periode ' . ($month ? \Carbon\Carbon::create()->month($month)->translatedFormat('F') . ' ' : '') . $year)

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.pasien') }}" class="nav-link"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
    <a href="{{ route('admin.obat') }}" class="nav-link"><i class="fa-solid fa-pills"></i> Data Obat</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
    <a href="{{ route('admin.broadcast.index') }}" class="nav-link"><i class="fa-solid fa-bullhorn"></i> Broadcast</a>
    <a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i>
        Pembayaran</a>
    <a href="{{ route('admin.laporan') }}" class="nav-link active"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('styles')
    <style>
        .report-nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .report-nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .report-nav a:hover,
        .report-nav a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .stat-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.85rem;
        }
    </style>
@endsection

@section('content')
    <!-- Report Navigation -->
    <div class="report-nav d-flex flex-wrap gap-2 justify-content-center">
        <a href="{{ route('admin.laporan') }}"><i class="fa-solid fa-chart-pie me-1"></i> Overview</a>
        <a href="{{ route('admin.laporan.keuangan') }}"><i class="fa-solid fa-coins me-1"></i> Keuangan</a>
        <a href="{{ route('admin.laporan.pembelian-obat') }}" class="active"><i class="fa-solid fa-cart-plus me-1"></i>
            Pembelian Obat</a>
        <a href="{{ route('admin.laporan.penjualan-obat') }}"><i class="fa-solid fa-prescription-bottle me-1"></i> Penjualan
            Obat</a>
        <a href="{{ route('admin.laporan.pemakaian-obat') }}"><i class="fa-solid fa-pills me-1"></i> Pemakaian Obat</a>
        <a href="{{ route('admin.laporan.penjualan-tindakan') }}"><i class="fa-solid fa-tooth me-1"></i> Penjualan
            Tindakan</a>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <form action="{{ route('admin.laporan.pembelian-obat') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Tahun</label>
                <select name="year" class="form-select rounded-pill">
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Bulan (Opsional)</label>
                <select name="month" class="form-select rounded-pill">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('admin.laporan.pembelian-obat.pdf', ['year' => $year, 'month' => $month]) }}"
                    class="btn btn-danger rounded-pill px-4">
                    <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Summary -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card p-4 bg-gradient text-white"
                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Total Pembelian</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fa-solid fa-cart-shopping fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 bg-gradient text-white"
                style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Jumlah Transaksi</p>
                        <h3 class="fw-bold mb-0">{{ $pembelian->count() }}</h3>
                    </div>
                    <i class="fa-solid fa-receipt fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 bg-gradient text-white"
                style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Total Item</p>
                        <h3 class="fw-bold mb-0">{{ $pembelian->sum('Jumlah') }} Unit</h3>
                    </div>
                    <i class="fa-solid fa-boxes-stacked fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-area text-primary me-2"></i>Tren Pembelian Bulanan</h5>
        <canvas id="pembelianChart" height="100"></canvas>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="p-4 border-bottom">
            <h5 class="fw-bold mb-0"><i class="fa-solid fa-table-list text-primary me-2"></i>Detail Pembelian Obat</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end pe-4">Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelian as $item)
                        <tr>
                            <td class="ps-4">{{ Carbon\Carbon::parse($item->Tanggal)->format('d M Y H:i') }}</td>
                            <td>
                                <span class="fw-bold">{{ $item->NamaObat }}</span>
                                <small class="d-block text-muted">{{ $item->IdObat }}</small>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $item->Jumlah }}
                                    {{ $item->Satuan }}</span></td>
                            <td>Rp {{ number_format($item->HargaBeli, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($item->Subtotal, 0, ',', '.') }}</td>
                            <td class="text-end pe-4"><span class="badge bg-light text-dark">{{ $item->CreatedBy }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p>Belum ada data pembelian obat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($pembelian->count() > 0)
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="4" class="ps-4 fw-bold">TOTAL</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const pembelianData = @json($pembelianBulanan->pluck('total', 'bulan'));
        const values = months.map((_, i) => pembelianData[i + 1] || 0);

        const ctx = document.getElementById('pembelianChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(245, 87, 108, 0.5)');
        gradient.addColorStop(1, 'rgba(245, 87, 108, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pembelian',
                    data: values,
                    borderColor: '#f5576c',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#f5576c'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => 'Rp ' + (v / 1000000).toFixed(1) + 'jt'
                        }
                    }
                }
            }
        });
    </script>
@endpush