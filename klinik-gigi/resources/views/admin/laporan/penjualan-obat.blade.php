@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Laporan Penjualan Obat')
@section('header-title', 'Laporan Penjualan Obat')
@section('header-subtitle', 'Data penjualan obat via pembayaran periode ' . ($month ? \Carbon\Carbon::create()->month($month)->translatedFormat('F') . ' ' : '') . $year)

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
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-bottom: 30px;
            padding: 5px;
        }

        .report-nav a {
            background: white;
            color: #64748b;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .report-nav a:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .report-nav a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border-color: transparent !important;
            box-shadow: 0 10px 15px -3px rgba(118, 75, 162, 0.3);
        }

        .report-nav a i {
            font-size: 1.1rem;
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
    <div class="report-nav">
        <a href="{{ route('admin.laporan') }}" class="{{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i> Overview
        </a>
        <a href="{{ route('admin.laporan.keuangan') }}" class="{{ request()->routeIs('admin.laporan.keuangan') ? 'active' : '' }}">
            <i class="fa-solid fa-coins"></i> Keuangan
        </a>
        <a href="{{ route('admin.laporan.pembelian-obat') }}" class="{{ request()->routeIs('admin.laporan.pembelian-obat') ? 'active' : '' }}">
            <i class="fa-solid fa-cart-plus"></i> Pembelian Obat
        </a>
        <a href="{{ route('admin.laporan.penjualan-obat') }}" class="{{ request()->routeIs('admin.laporan.penjualan-obat') ? 'active' : '' }}">
            <i class="fa-solid fa-prescription-bottle"></i> Penjualan Obat
         </a>
        <a href="{{ route('admin.laporan.pendapatan-obat') }}" class="{{ request()->routeIs('admin.laporan.pendapatan-obat') ? 'active' : '' }}">
            <i class="fa-solid fa-money-bill-trend-up"></i> Pendapatan per Obat
        </a>
        <a href="{{ route('admin.laporan.pemakaian-obat') }}" class="{{ request()->routeIs('admin.laporan.pemakaian-obat') ? 'active' : '' }}">
            <i class="fa-solid fa-pills"></i> Pemakaian Obat
        </a>
        <a href="{{ route('admin.laporan.penjualan-tindakan') }}" class="{{ request()->routeIs('admin.laporan.penjualan-tindakan') ? 'active' : '' }}">
            <i class="fa-solid fa-tooth"></i> Penjualan Tindakan
        </a>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <form action="{{ route('admin.laporan.penjualan-obat') }}" method="GET" class="row g-3 align-items-end">
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
                <a href="{{ route('admin.laporan.penjualan-obat.pdf', ['year' => $year, 'month' => $month]) }}"
                    class="btn btn-danger rounded-pill px-4">
                    <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Summary -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card p-4 text-white"
                style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-medium">Total Penjualan Obat</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fa-solid fa-sack-dollar fa-3x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card p-4 text-white"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-medium">Total Jenis Obat Terjual</p>
                        <h3 class="fw-bold mb-0">{{ $penjualan->count() }} Jenis</h3>
                    </div>
                    <i class="fa-solid fa-capsules fa-3x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-line text-success me-2"></i>Tren Penjualan Bulanan</h5>
        <canvas id="penjualanChart" height="100"></canvas>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="p-4 border-bottom">
            <h5 class="fw-bold mb-0"><i class="fa-solid fa-ranking-star text-warning me-2"></i>Ranking Penjualan Obat</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Nama Obat</th>
                        <th>Satuan</th>
                        <th class="text-center">Total Terjual</th>
                        <th class="text-end pe-4">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $index => $item)
                        <tr>
                            <td class="ps-4">
                                @if($index < 3)
                                    <span
                                        class="badge bg-{{ ['warning', 'secondary', 'danger'][$index] }} rounded-pill">{{ $index + 1 }}</span>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td class="fw-bold">{{ $item->NamaObat }}</td>
                            <td>{{ $item->Satuan }}</td>
                            <td class="text-center"><span
                                    class="badge bg-success-subtle text-success">{{ $item->TotalJumlah }}</span></td>
                            <td class="text-end pe-4 fw-bold text-success">Rp
                                {{ number_format($item->TotalPenjualan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p>Belum ada data penjualan obat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($penjualan->count() > 0)
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="3" class="ps-4 fw-bold">TOTAL</td>
                            <td class="text-center fw-bold">{{ $penjualan->sum('TotalJumlah') }}</td>
                            <td class="text-end pe-4 fw-bold text-success">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                            </td>
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
        const penjualanData = @json($penjualanBulanan->pluck('total', 'bulan'));
        const values = months.map((_, i) => penjualanData[i + 1] || 0);

        const ctx = document.getElementById('penjualanChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(56, 239, 125, 0.5)');
        gradient.addColorStop(1, 'rgba(17, 153, 142, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Penjualan',
                    data: values,
                    borderColor: '#11998e',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#11998e'
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