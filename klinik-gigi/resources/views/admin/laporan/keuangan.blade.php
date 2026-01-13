@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Laporan Keuangan')
@section('header-title', 'Laporan Keuangan')
@section('header-subtitle', 'Pendapatan & Pengeluaran Periode ' . ($month ? \Carbon\Carbon::create()->month($month)->translatedFormat('F') . ' ' : '') . $year)

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
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
@endsection

@section('content')
    <!-- Report Navigation -->
    <div class="report-nav d-flex flex-wrap gap-2 justify-content-center">
        <a href="{{ route('admin.laporan') }}"><i class="fa-solid fa-chart-pie me-1"></i> Overview</a>
        <a href="{{ route('admin.laporan.keuangan') }}" class="active"><i class="fa-solid fa-coins me-1"></i> Keuangan</a>
        <a href="{{ route('admin.laporan.pembelian-obat') }}"><i class="fa-solid fa-cart-plus me-1"></i> Pembelian Obat</a>
        <a href="{{ route('admin.laporan.penjualan-obat') }}"><i class="fa-solid fa-prescription-bottle me-1"></i> Penjualan
            Obat</a>
        <a href="{{ route('admin.laporan.pemakaian-obat') }}"><i class="fa-solid fa-pills me-1"></i> Pemakaian Obat</a>
        <a href="{{ route('admin.laporan.penjualan-tindakan') }}"><i class="fa-solid fa-tooth me-1"></i> Penjualan
            Tindakan</a>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <form action="{{ route('admin.laporan.keuangan') }}" method="GET" class="row g-3 align-items-end">
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
                            {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('admin.laporan.keuangan.pdf', ['year' => $year, 'month' => $month]) }}"
                    class="btn btn-danger rounded-pill px-4">
                    <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card p-4 border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Pendapatan Kotor</p>
                        <h3 class="fw-bold text-success mb-0">Rp {{ number_format($pendapatanKotor, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-4">
                        <i class="fa-solid fa-arrow-trend-up fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 border-start border-danger border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Pengeluaran (Modal Obat)</p>
                        <h3 class="fw-bold text-danger mb-0">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-danger-subtle text-danger p-3 rounded-4">
                        <i class="fa-solid fa-arrow-trend-down fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 border-start border-primary border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Laba Kotor</p>
                        <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($labaKotor, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-4">
                        <i class="fa-solid fa-wallet fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-column text-success me-2"></i>Pendapatan per Bulan</h5>
                <canvas id="pendapatanChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-column text-danger me-2"></i>Pengeluaran per Bulan</h5>
                <canvas id="pengeluaranChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Detail Transactions -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="p-4 border-bottom">
            <h5 class="fw-bold mb-0"><i class="fa-solid fa-list-check text-primary me-2"></i>Detail Transaksi Pendapatan
                Terbaru</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>ID Pembayaran</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th class="text-end pe-4">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailPendapatan as $p)
                        <tr>
                            <td class="ps-4">{{ Carbon\Carbon::parse($p->TanggalPembayaran)->format('d M Y') }}</td>
                            <td><span class="badge bg-light text-dark">{{ $p->IdPembayaran }}</span></td>
                            <td>{{ $p->rekamMedis->pasien->Nama ?? '-' }}</td>
                            <td>{{ $p->rekamMedis->dokter->Nama ?? '-' }}</td>
                            <td class="text-end pe-4 fw-bold text-success">Rp {{ number_format($p->TotalBayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p>Belum ada data transaksi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Pendapatan Chart
        const pendapatanData = @json($pendapatanBulanan->pluck('total', 'bulan'));
        const pendapatanValues = months.map((_, i) => pendapatanData[i + 1] || 0);

        new Chart(document.getElementById('pendapatanChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pendapatan',
                    data: pendapatanValues,
                    backgroundColor: 'rgba(25, 135, 84, 0.7)',
                    borderRadius: 8
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

        // Pengeluaran Chart
        const pengeluaranData = @json($pengeluaranBulanan->pluck('total', 'bulan'));
        const pengeluaranValues = months.map((_, i) => pengeluaranData[i + 1] || 0);

        new Chart(document.getElementById('pengeluaranChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pengeluaran',
                    data: pengeluaranValues,
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderRadius: 8
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