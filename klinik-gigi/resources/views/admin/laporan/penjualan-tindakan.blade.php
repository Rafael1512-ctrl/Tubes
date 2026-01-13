@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Laporan Penjualan Tindakan')
@section('header-title', 'Laporan Penjualan Tindakan')
@section('header-subtitle', 'Data pendapatan dari layanan tindakan periode ' . ($month ? \Carbon\Carbon::create()->month($month)->translatedFormat('F') . ' ' : '') . $year)

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

        .category-badge {
            font-size: 0.7rem;
            padding: 5px 10px;
        }
    </style>
@endsection

@section('content')
    <!-- Report Navigation -->
    <div class="report-nav d-flex flex-wrap gap-2 justify-content-center">
        <a href="{{ route('admin.laporan') }}"><i class="fa-solid fa-chart-pie me-1"></i> Overview</a>
        <a href="{{ route('admin.laporan.keuangan') }}"><i class="fa-solid fa-coins me-1"></i> Keuangan</a>
        <a href="{{ route('admin.laporan.pembelian-obat') }}"><i class="fa-solid fa-cart-plus me-1"></i> Pembelian Obat</a>
        <a href="{{ route('admin.laporan.penjualan-obat') }}"><i class="fa-solid fa-prescription-bottle me-1"></i> Penjualan
            Obat</a>
        <a href="{{ route('admin.laporan.pemakaian-obat') }}"><i class="fa-solid fa-pills me-1"></i> Pemakaian Obat</a>
        <a href="{{ route('admin.laporan.penjualan-tindakan') }}" class="active"><i class="fa-solid fa-tooth me-1"></i>
            Penjualan Tindakan</a>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <form action="{{ route('admin.laporan.penjualan-tindakan') }}" method="GET" class="row g-3 align-items-end">
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
                <a href="{{ route('admin.laporan.penjualan-tindakan.pdf', ['year' => $year, 'month' => $month]) }}"
                    class="btn btn-danger rounded-pill px-4">
                    <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Summary -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card p-4 bg-gradient text-white"
                style="background: linear-gradient(135deg, #3494E6 0%, #EC6EAD 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Total Pendapatan Tindakan</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fa-solid fa-hand-holding-medical fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-4 bg-gradient text-white"
                style="background: linear-gradient(135deg, #f5af19 0%, #f12711 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Total Tindakan</p>
                        <h3 class="fw-bold mb-0">{{ $penjualan->sum('JumlahTindakan') }}x</h3>
                    </div>
                    <i class="fa-solid fa-stethoscope fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-4 bg-gradient text-white"
                style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Jenis Tindakan</p>
                        <h3 class="fw-bold mb-0">{{ $penjualan->count() }}</h3>
                    </div>
                    <i class="fa-solid fa-list-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-line text-primary me-2"></i>Tren Pendapatan Tindakan
                    Bulanan</h5>
                <canvas id="tindakanChart" height="120"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-pie text-warning me-2"></i>Top 5 Tindakan</h5>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="p-4 border-bottom">
            <h5 class="fw-bold mb-0"><i class="fa-solid fa-trophy text-warning me-2"></i>Ranking Penjualan Tindakan</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Nama Tindakan</th>
                        <th>Kategori</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end pe-4">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $index => $item)
                        <tr>
                            <td class="ps-4">
                                @if($index < 3)
                                    <span class="badge bg-{{ ['warning', 'secondary', 'danger'][$index] }} rounded-circle"
                                        style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td class="fw-bold">{{ $item->NamaTindakan }}</td>
                            <td>
                                @php
                                    $catColors = [
                                        'Pemeriksaan' => 'info',
                                        'Perawatan' => 'success',
                                        'Bedah' => 'danger',
                                        'Estetik' => 'warning',
                                    ];
                                    $color = $catColors[$item->Kategori] ?? 'secondary';
                                @endphp
                                <span
                                    class="badge bg-{{ $color }}-subtle text-{{ $color }} category-badge">{{ $item->Kategori ?? 'Umum' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary">{{ $item->JumlahTindakan }}x</span>
                            </td>
                            <td class="text-end pe-4 fw-bold text-success">Rp
                                {{ number_format($item->TotalPendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p>Belum ada data penjualan tindakan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($penjualan->count() > 0)
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="3" class="ps-4 fw-bold">TOTAL</td>
                            <td class="text-center fw-bold">{{ $penjualan->sum('JumlahTindakan') }}x</td>
                            <td class="text-end pe-4 fw-bold text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
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
        const tindakanData = @json($penjualanBulanan->pluck('total', 'bulan'));
        const values = months.map((_, i) => tindakanData[i + 1] || 0);

        const ctx = document.getElementById('tindakanChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(52, 148, 230, 0.5)');
        gradient.addColorStop(1, 'rgba(236, 110, 173, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pendapatan',
                    data: values,
                    borderColor: '#3494E6',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#EC6EAD'
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

        // Pie Chart
        const topData = @json($penjualan->take(5));
        const pieLabels = topData.map(d => d.NamaTindakan);
        const pieValues = topData.map(d => d.TotalPendapatan);

        new Chart(document.getElementById('pieChart'), {
            type: 'doughnut',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieValues,
                    backgroundColor: [
                        '#3494E6', '#EC6EAD', '#f5af19', '#11998e', '#667eea'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 15 }
                    }
                }
            }
        });
    </script>
@endpush