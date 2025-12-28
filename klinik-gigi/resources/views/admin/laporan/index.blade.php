@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Laporan Klinik')
@section('header-title', 'Laporan & Analitik')
@section('header-subtitle', 'Pantau performa klinik Dental Zenith periode ' . $year)

@section('sidebar-menu')
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('admin.booking') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.pasien') }}" class="nav-link"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
<a href="{{ route('admin.obat') }}" class="nav-link"><i class="fa-solid fa-pills"></i> Data Obat</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
<a href="{{ route('admin.laporan') }}" class="nav-link active"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('styles')
<style>
    .report-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .report-card:hover {
        transform: translateY(-5px);
    }
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
</style>
@endsection

@section('content')

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
        <div class="card report-card p-4 bg-white">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">Total Pendapatan Th. {{ $year }}</small>
                    <h4 class="fw-bold mb-0 text-primary">Rp {{ number_format($totalRevenueYear, 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="small">
                <span class="text-success fw-bold"><i class="fa-solid fa-arrow-up me-1"></i> 12%</span>
                <span class="text-muted ms-1">dari tahun lalu</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card report-card p-4 bg-white">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">Pasien Baru Th. {{ $year }}</small>
                    <h4 class="fw-bold mb-0 text-success">{{ $totalPasienNew }} Pasien</h4>
                </div>
            </div>
            <div class="small">
                 <span class="text-success fw-bold"><i class="fa-solid fa-arrow-up me-1"></i> 8%</span>
                 <span class="text-muted ms-1">pertumbuhan</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card report-card p-4 bg-white">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="fa-solid fa-notes-medical"></i>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">Total Pemeriksaan Th. {{ $year }}</small>
                    <h4 class="fw-bold mb-0 text-info">{{ $totalPemeriksaan }}</h4>
                </div>
            </div>
            <div class="small">
                 <span class="text-muted">Target tercapai 85%</span>
            </div>
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
                        <div class="fw-bold text-muted" style="width: 20px;">#{{ $index+1 }}</div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $t->NamaTindakan }}</h6>
                            <small class="text-muted">{{ $t->total }} Kali dilakukan</small>
                        </div>
                    </div>
                    @php $colors = ['primary', 'success', 'info', 'warning', 'secondary']; @endphp
                    <span class="badge bg-{{ $colors[$index] ?? 'dark' }}-subtle text-{{ $colors[$index] ?? 'dark' }}-emphasis">{{ round(($t->total / max(1, $totalPemeriksaan)) * 100, 1) }}%</span>
                </div>
                @endforeach
            </div>

            <hr class="my-4">
            
            <div class="bg-primary text-white p-3 rounded-3 mt-auto">
                 <h6 class="fw-bold mb-2">Insight Analitik</h6>
                 <p class="small mb-0 opacity-75">
                     Layanan <strong>{{ $popularTindakan->first()->NamaTindakan ?? '-' }}</strong> menjadi kontributor utama kunjungan pasien tahun ini.
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
                        label: function(context) {
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
                        callback: function(value) {
                            return 'Rp ' + (value/1000000) + 'jt';
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
