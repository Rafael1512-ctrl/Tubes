@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Manajemen Booking Pasien')
@section('header-title','Manajemen Booking Pasien')
@section('header-subtitle','Kelola booking pasien dengan mudah')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link active"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
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

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem;
    }

    .table tbody tr {
        transition: all 0.2s;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Tabs */
    .nav-tabs-custom {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 1.5rem;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s;
    }

    .nav-tabs-custom .nav-link.active {
        color: #0d6efd;
        border-bottom: 3px solid #0d6efd;
        background: transparent;
    }

    /* Animations */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-down {
        animation: slideDown 0.4s ease-out;
    }

    /* Alert Styles */
    .alert {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-left: 5px solid;
    }

    .alert-success {
        border-left-color: #28a745;
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        border-left-color: #dc3545;
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert i {
        opacity: 0.8;
    }
</style>
@endsection

@section('content')

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-slide-down" role="alert" id="success-alert">
    <div class="d-flex align-items-center">
        <i class="fa-solid fa-circle-check me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Berhasil!</strong>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show animate-slide-down" role="alert" id="error-alert">
    <div class="d-flex align-items-center">
        <i class="fa-solid fa-circle-exclamation me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Gagal!</strong>
            <p class="mb-0">{{ session('error') }}</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Tabs Navigation --}}
<ul class="nav nav-tabs-custom">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.jadwal') }}">
            <i class="fa-solid fa-calendar"></i> Jadwal Dokter
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('admin.booking') }}">
            <i class="fa-solid fa-list"></i> Daftar Booking
        </a>
    </li>
</ul>

{{-- Filter & Search Section --}}
<div class="filter-section">
    <form method="GET" action="{{ route('admin.booking') }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-bold">Cari Booking / Pasien</label>
            <input type="text" name="search" class="form-control" placeholder="ID Booking atau Nama Pasien" 
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="PRESENT" {{ request('status') == 'PRESENT' ? 'selected' : '' }}>Present</option>
                <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Tanggal</label>
            <input type="date" name="tanggal" class="form-select" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-search"></i> Cari
            </button>
            <a href="{{ route('admin.booking') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-right"></i> Reset
            </a>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ route('admin.booking.create') }}" class="btn btn-success w-100">
                <i class="fa-solid fa-plus"></i> Tambah Booking
            </a>
        </div>
    </form>
</div>

{{-- Table View --}}
<div class="table-container">
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td><strong>{{ $booking->IdBooking }}</strong></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="fa-solid fa-user text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $booking->pasien->Nama ?? '-' }}</div>
                                <small class="text-muted">{{ $booking->pasien->NoTelp ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $booking->jadwal->dokter->Nama ?? '-' }}</div>
                        <small class="text-muted">{{ ucfirst($booking->jadwal->dokter->Jabatan ?? '-') }}</small>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $booking->jadwal->formatted_tanggal ?? '-' }}</div>
                        <small class="text-muted">{{ $booking->jadwal->formatted_jam ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $booking->jadwal->sesi == 'Pagi' ? 'warning' : 'primary' }}">
                            {{ $booking->jadwal->sesi ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $booking->Status == 'CANCELLED' ? 'danger' : 'success' }}">
                            {{ $booking->Status }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.booking.edit', $booking->IdBooking) }}" 
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            @if($booking->Status != 'CANCELLED')
                            <form action="{{ route('admin.booking.destroy', $booking->IdBooking) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Yakin ingin membatalkan booking ini?')"
                                  style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Cancel">
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada data booking</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($bookings->hasPages())
    <div class="mt-3">
        {{ $bookings->links() }}
    </div>
    @endif
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
