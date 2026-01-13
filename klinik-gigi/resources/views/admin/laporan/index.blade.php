@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Laporan Klinik')
@section('header-title', 'Laporan & Analitik')
@section('header-subtitle', 'Pantau performa klinik Dental Zenith periode ' . $year)

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i
            class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link {{ request()->routeIs('admin.booking*') ? 'active' : '' }}"><i
            class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.pasien') }}" class="nav-link {{ request()->routeIs('admin.pasien*') ? 'active' : '' }}"><i
            class="fa-solid fa-hospital-user"></i> Data Pasien</a>
    <a href="{{ route('admin.obat') }}" class="nav-link {{ request()->routeIs('admin.obat*') ? 'active' : '' }}"><i
            class="fa-solid fa-pills"></i> Data Obat</a>
    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i
            class="fa-solid fa-users"></i> Manajemen User</a>
    <a href="{{ route('admin.broadcast.index') }}"
        class="nav-link {{ request()->routeIs('admin.broadcast*') ? 'active' : '' }}"><i class="fa-solid fa-bullhorn"></i>
        Broadcast</a>

    <a href="{{ route('admin.pembayaran') }}"
        class="nav-link {{ request()->routeIs('admin.pembayaran*') ? 'active' : '' }}"><i
            class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
    <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}"><i
            class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('styles')
    <style>
        .stat-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
        }

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

    <!-- Filter Year -->
    <div class="card-custom mb-4 p-3 bg-white border-0 shadow-sm d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Filter Periode</h6>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.laporan') }}" method="GET" class="d-flex gap-2">
                <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('admin.laporan.pdf', ['year' => $year]) }}" class="btn btn-sm btn-danger">
                <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
            </a>
        </div>
    </div>

    <!-- Summary Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card p-4 text-white"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-medium">Total Pendapatan ({{ $year }})</p>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($totalRevenueYear, 0, ',', '.') }}</h4>
                    </div>
                    <i class="fa-solid fa-money-bill-trend-up fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 text-white"
                style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-medium">Pasien Baru</p>
                        <h4 class="fw-bold mb-0">{{ $totalPasienNew }} Pasien</h4>
                    </div>
                    <i class="fa-solid fa-user-plus fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 text-white"
                style="background: linear-gradient(135deg, #f5af19 0%, #f12711 100%) !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75 fw-medium">Total Pemeriksaan</p>
                        <h4 class="fw-bold mb-0">{{ $totalPemeriksaan }}</h4>
                    </div>
                    <i class="fa-solid fa-notes-medical fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Row 2: Finance Detail -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card p-4 bg-white border-start border-danger border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">Modal Obat (COGS)</small>
                        <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format($medicineCost, 0, ',', '.') }}</h4>
                    </div>
                    <div class="bg-danger-subtle text-danger p-3 rounded-pill">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                </div>
                <p class="small text-muted mb-0 mt-2">Nilai beli obat terpakai tahun {{ $year }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card p-4 bg-white border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">Laba Kotor Estimasi</small>
                        <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($estimatedProfit, 0, ',', '.') }}</h4>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-pill">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                </div>
                <p class="small text-muted mb-0 mt-2">Pendapatan dikurangi modal obat</p>
            </div>
        </div>
    </div>

        <div class="row g-4">
            <!-- Revenue Chart -->
            <div class="col-lg-8">
                <div class="card-custom bg-white border-0 shadow-sm p-4 h-100">
                    <h5 class="fw-bold mb-4">Grafik Pendapatan Bulanan ({{ $year }})</h5>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Popular Procedures -->
            <div class="col-lg-4">
                <div class="card-custom bg-white border-0 shadow-sm p-4 h-100">
                    <h5 class="fw-bold mb-4">Layanan Terpopuler</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach($popularTindakan as $index => $t)
                            <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="fw-bold text-muted" style="width: 20px;">#{{ $index + 1 }}</div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $t->NamaTindakan }}</h6>
                                        <small class="text-muted">{{ $t->total }} Kali dilakukan</small>
                                    </div>
                                </div>
                                @php $colors = ['primary', 'success', 'info', 'warning', 'secondary']; @endphp
                                <span
                                    class="badge bg-{{ $colors[$index] ?? 'dark' }}-subtle text-{{ $colors[$index] ?? 'dark' }}-emphasis">{{ round(($t->total / max(1, $totalPemeriksaan)) * 100, 1) }}%</span>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-4">

                    <div class="bg-primary text-white p-3 rounded-3 mt-auto">
                        <h6 class="fw-bold mb-2">Insight Analitik</h6>
                        <p class="small mb-0 opacity-75">
                            Layanan <strong>{{ $popularTindakan->first()->NamaTindakan ?? '-' }}</strong> menjadi
                            kontributor utama kunjungan pasien tahun ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>

@endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');

            // Create gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(13, 110, 253, 0.5)');
            gradient.addColorStop(1, 'rgba(13, 110, 253, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($monthlyRevenue),
                        borderColor: '#0d6efd',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0d6efd',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + (value / 1000000) + 'jt';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        </script>
    @endpush