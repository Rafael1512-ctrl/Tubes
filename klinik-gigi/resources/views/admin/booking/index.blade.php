@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Manajemen Booking Pasien')
@section('header-title','Manajemen Booking Pasien')
@section('header-subtitle','Kelola booking pasien dengan mudah')

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link active"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.pasien') }}" class="nav-link"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
    <a href="{{ route('admin.obat') }}" class="nav-link"><i class="fa-solid fa-pills"></i> Data Obat</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
    <a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
    <a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('styles')
<style>
    /* Table Styles */
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
        transform: scale(1.002);
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
        border: none;
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
    
    /* Status Badge Styling */
    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

{{-- Tabs Navigation --}}
<ul class="nav nav-tabs-custom d-flex gap-2 mb-4 border-bottom-0">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.jadwal') }}">
            <i class="fa-solid fa-calendar-days me-2"></i>Jadwal Dokter
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('admin.booking') }}">
            <i class="fa-solid fa-clipboard-list me-2"></i>Daftar Booking
        </a>
    </li>
</ul>

{{-- Filter & Search Section --}}
<div class="filter-section">
    <form method="GET" action="{{ route('admin.booking') }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-bold">Cari Booking / Pasien</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="ID atau Nama Pasien" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="PRESENT" {{ request('status') == 'PRESENT' ? 'selected' : '' }}>Present</option>
                <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-filter me-2"></i> Terapkan
            </button>
            <a href="{{ route('admin.booking') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-right me-2"></i> Reset
            </a>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ route('admin.booking.create') }}" class="btn btn-success w-100">
                <i class="fa-solid fa-plus me-2"></i> Tambah Booking
            </a>
        </div>
    </form>
</div>

{{-- Table View --}}
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-dark">
            <i class="fa-solid fa-list-check me-2 text-primary"></i>
            Data Booking Pasien
        </h4>
        <div class="text-muted small">
            Menampilkan data booking terbaru
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID Booking</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Tanggal & Jam</th>
                    <th>Sesi</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td><span class="fw-bold text-primary">#{{ $booking->IdBooking }}</span></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="fa-solid fa-user text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $booking->pasien->Nama ?? '-' }}</div>
                                <small class="text-muted"><i class="fa-solid fa-phone fa-xs me-1"></i>{{ $booking->pasien->NoTelp ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="fa-solid fa-user-doctor text-info"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $booking->jadwal->dokter->Nama ?? '-' }}</div>
                                <small class="text-muted">{{ ucfirst($booking->jadwal->dokter->Jabatan ?? '-') }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $booking->jadwal->formatted_tanggal ?? '-' }}</div>
                        <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $booking->jadwal->formatted_jam ?? '-' }}</small>
                    </td>
                    <td>
                        @php
                            $sesiClass = $booking->jadwal->sesi == 'Pagi' ? 'warning' : 'primary';
                            $sesiIcon = $booking->jadwal->sesi == 'Pagi' ? 'fa-sun' : 'fa-moon';
                        @endphp
                        <span class="badge bg-{{ $sesiClass }} bg-opacity-10 text-{{ $sesiClass }} border border-{{ $sesiClass }} border-opacity-25 px-3 py-2 rounded-pill">
                            <i class="fa-solid {{ $sesiIcon }} me-1"></i>{{ $booking->jadwal->sesi ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @php
                            $statusClass = match($booking->Status) {
                                'COMPLETED' => 'success',
                                'PRESENT' => 'info',
                                'CANCELLED' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge-status bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} border border-{{ $statusClass }} border-opacity-25">
                            {{ $booking->Status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            @if($booking->Status == 'COMPLETED')
                                <a href="{{ route('admin.pasien.history', $booking->PasienID) }}" 
                                   class="btn btn-info btn-sm rounded-circle" title="Lihat Riwayat Medis"
                                   style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-file-medical text-white"></i>
                                </a>
                            @else
                                <a href="{{ route('admin.booking.edit', $booking->IdBooking) }}" 
                                   class="btn btn-primary btn-sm rounded-circle" title="Edit Booking"
                                   style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                
                                @if($booking->Status != 'CANCELLED')
                                <form action="{{ route('admin.booking.destroy', $booking->IdBooking) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin membatalkan booking ini?')"
                                      style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle" title="Batalkan Booking"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted opacity-50 mb-3">
                            <i class="fa-solid fa-inbox fa-4x"></i>
                        </div>
                        <h6 class="text-muted fw-bold">Tidak ada data booking ditemukan</h6>
                        <p class="text-muted small">Coba ubah filter atau tambah booking baru.</p>
                        <a href="{{ route('admin.booking.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Booking Baru
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->links() }}
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
