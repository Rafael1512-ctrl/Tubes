@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Manajemen Jadwal Dokter')
@section('header-title','Manajemen Jadwal Dokter')
@section('header-subtitle','Kelola jadwal praktek dokter dengan mudah')

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link active"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
    <a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
    <a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('styles')
<style>
    /* Table Styles (Matched with Booking) */
    .table-container {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .table { margin-bottom: 0; }
    
    .table thead th {
        background: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem;
        white-space: nowrap;
        color: #495057;
    }

    .table tbody tr { transition: all 0.2s; }
    .table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.005);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #6c757d;
    }
    
    .table tbody td .fw-bold {
        color: #343a40;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Tabs Override */
    .nav-tabs-custom {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 20px;
    }
    .nav-tabs-custom .nav-link {
        color: #64748b;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .nav-tabs-custom .nav-link.active {
        color: #0ea5e9;
        background: #f0f9ff;
        border-bottom: none;
    }
    .nav-tabs-custom .nav-link:hover:not(.active) {
        background: #f8fafc;
        color: #0ea5e9;
    }
    
    /* Animations */
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-down { animation: slideDown 0.4s ease-out; }
</style>
@endsection

@section('content')

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-slide-down shadow-sm border-0 mb-4" role="alert" id="success-alert">
    <div class="d-flex align-items-center">
        <div class="bg-success text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa-solid fa-check"></i></div>
        <div><strong>Berhasil!</strong> {{ session('success') }}</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" id="error-alert">
    <div class="d-flex align-items-center">
         <div class="bg-danger text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa-solid fa-xmark"></i></div>
        <div><strong>Gagal!</strong> {{ session('error') }}</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Tabs --}}
<ul class="nav nav-tabs-custom d-flex gap-2 mb-4 border-bottom-0">
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('admin.jadwal') }}">
            <i class="fa-solid fa-calendar-days me-2"></i>Jadwal Dokter
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.booking') }}">
            <i class="fa-solid fa-clipboard-list me-2"></i>Daftar Booking
        </a>
    </li>
</ul>

{{-- Filter Section --}}
<div class="filter-section">
    <form method="GET" action="{{ route('admin.jadwal') }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-bold">Dokter</label>
            <select name="dokter" class="form-select">
                <option value="">Semua Dokter</option>
                @foreach($dokters as $dokter)
                    <option value="{{ $dokter->PegawaiID }}" {{ request('dokter') == $dokter->PegawaiID ? 'selected' : '' }}>
                        {{ $dokter->Nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Bulan</label>
            <select name="month" class="form-select">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Tahun</label>
            <select name="year" class="form-select">
                @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-filter me-2"></i> Terapkan
            </button>
            <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-right me-2"></i> Reset
            </a>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-success w-100">
                <i class="fa-solid fa-plus me-2"></i> Buat Jadwal
            </a>
        </div>
    </form>
</div>

{{-- Table View --}}
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-dark">
            <i class="fa-regular fa-calendar-check me-2 text-primary"></i>
            Jadwal Praktek: {{ \Carbon\Carbon::create($year, $month)->isoFormat('MMMM Y') }}
        </h4>
        <div class="text-muted small">
            Menampilkan jadwal tersaring
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Dokter</th>
                    <th>Jabatan</th>
                    <th>Hari / Tanggal</th>
                    <th>Jam Praktek</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $jadwal)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="fa-solid fa-user-doctor text-primary"></i>
                            </div>
                            <div class="fw-bold">{{ $jadwal->dokter->Nama ?? '-' }}</div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ ucfirst($jadwal->dokter->Jabatan ?? '-') }}</span>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $jadwal->Tanggal->isoFormat('dddd') }}</div>
                        <small class="text-muted">{{ $jadwal->Tanggal->isoFormat('D MMMM Y') }}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($jadwal->JamMulai->format('H') < 12)
                                <i class="fa-regular fa-sun text-warning me-2" title="Pagi"></i>
                            @else
                                <i class="fa-regular fa-moon text-primary me-2" title="Sore"></i>
                            @endif
                            <span class="fw-bold text-dark">
                                {{ substr($jadwal->JamMulai->format('H:i'), 0, 5) }} - {{ substr($jadwal->JamAkhir->format('H:i'), 0, 5) }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column" style="width: 120px;">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-bold">{{ $jadwal->sisa_kapasitas }} Sisa</span>
                                <span class="text-muted">/ {{ $jadwal->Kapasitas }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                @php $percent = (($jadwal->Kapasitas - $jadwal->sisa_kapasitas) / $jadwal->Kapasitas) * 100; @endphp
                                <div class="progress-bar bg-{{ $percent > 80 ? 'danger' : 'success' }}" role="progressbar" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($jadwal->Status == 'Available')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Available</span>
                        @elseif($jadwal->Status == 'Full')
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">Full</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill">{{ $jadwal->Status }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                         <a href="{{ route('admin.jadwal.edit', $jadwal->IdJadwal) }}" class="btn btn-primary btn-sm rounded-circle" title="Edit Jadwal" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted opacity-50 mb-3">
                            <i class="fa-regular fa-calendar-xmark fa-4x"></i>
                        </div>
                        <h6 class="text-muted fw-bold">Tidak ada jadwal ditemukan</h6>
                        <p class="text-muted small">Coba ubah filter bulan/tahun atau buat jadwal baru.</p>
                        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fa-solid fa-plus me-1"></i> Buat Jadwal Baru
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // Auto-dismiss alerts
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    
    function autoDismissAlert(alertElement, delay = 5000) {
        if (alertElement) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }, delay);
        }
    }
    
    autoDismissAlert(successAlert);
    autoDismissAlert(errorAlert);
</script>

@endsection
