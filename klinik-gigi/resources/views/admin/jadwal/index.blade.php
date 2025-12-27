@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Manajemen Jadwal Dokter')
@section('header-title','Manajemen Jadwal Dokter')
@section('header-subtitle','Kelola jadwal praktek dokter dengan mudah')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link active"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
@endsection

@section('styles')
<style>
    /* Calendar Styles */
    .calendar-container {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }

    .calendar-day-header {
        text-align: center;
        font-weight: 600;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        color: #495057;
        font-size: 0.9rem;
    }

    .calendar-day {
        min-height: 120px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 8px;
        background: white;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .calendar-day:hover {
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        transform: translateY(-2px);
    }

    .calendar-day.other-month {
        background: #f8f9fa;
        opacity: 0.5;
    }

    .calendar-day.today {
        border-color: #0d6efd;
        background: #e7f1ff;
    }

    .day-number {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
    }

    .jadwal-item {
        font-size: 0.75rem;
        padding: 4px 6px;
        margin-bottom: 3px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .jadwal-item:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .jadwal-available {
        background: #d1e7dd;
        color: #0f5132;
        border-left: 3px solid #198754;
    }

    .jadwal-full {
        background: #f8d7da;
        color: #842029;
        border-left: 3px solid #dc3545;
    }

    .jadwal-cancelled {
        background: #e2e3e5;
        color: #41464b;
        border-left: 3px solid #6c757d;
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

    .nav-tabs-custom .nav-link:hover {
        color: #0d6efd;
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

{{-- Alert untuk pesan sukses --}}
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

{{-- Alert untuk pesan error --}}
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
        <a class="nav-link active" href="{{ route('admin.jadwal') }}">
            <i class="fa-solid fa-calendar"></i> Jadwal Dokter
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.booking') }}">
            <i class="fa-solid fa-list"></i> Daftar Booking
        </a>
    </li>
</ul>

{{-- Filter Section --}}
<div class="filter-section">
    <form method="GET" action="{{ route('admin.jadwal') }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-bold">Pilih Dokter</label>
            <select name="dokter" class="form-select">
                <option value="">Semua Dokter</option>
                @foreach($dokters as $dokter)
                    <option value="{{ $dokter->PegawaiID }}" {{ request('dokter') == $dokter->PegawaiID ? 'selected' : '' }}>
                        {{ $dokter->Nama }} ({{ ucfirst($dokter->Jabatan) }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-bold">Bulan</label>
            <select name="month" class="form-select">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
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
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-right"></i> Reset
            </a>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-success w-100">
                <i class="fa-solid fa-plus"></i> Tambah Jadwal
            </a>
        </div>
    </form>
</div>

{{-- Calendar View --}}
<div class="calendar-container">
    <div class="calendar-header">
        <h4 class="mb-0">
            <i class="fa-solid fa-calendar-days text-primary"></i>
            {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
        </h4>
        <div class="d-flex gap-2">
            <span class="badge bg-success">Available</span>
            <span class="badge bg-danger">Full</span>
            <span class="badge bg-secondary">Cancelled</span>
        </div>
    </div>

    <div class="calendar-grid">
        {{-- Day Headers --}}
        <div class="calendar-day-header">Min</div>
        <div class="calendar-day-header">Sen</div>
        <div class="calendar-day-header">Sel</div>
        <div class="calendar-day-header">Rab</div>
        <div class="calendar-day-header">Kam</div>
        <div class="calendar-day-header">Jum</div>
        <div class="calendar-day-header">Sab</div>

        @php
            $firstDay = \Carbon\Carbon::create($year, $month, 1);
            $lastDay = $firstDay->copy()->endOfMonth();
            $startDay = $firstDay->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
            $endDay = $lastDay->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
            $currentDay = $startDay->copy();
        @endphp

        @while($currentDay <= $endDay)
            @php
                $isCurrentMonth = $currentDay->month == $month;
                $isToday = $currentDay->isToday();
                $dayJadwals = $jadwals->filter(function($jadwal) use ($currentDay) {
                    return $jadwal->Tanggal->isSameDay($currentDay);
                });
            @endphp

            <div class="calendar-day {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }}">
                <div class="day-number">{{ $currentDay->day }}</div>
                
                @foreach($dayJadwals as $jadwal)
                    <div class="jadwal-item jadwal-{{ strtolower($jadwal->Status) }}" 
                         onclick="window.location.href='{{ route('admin.jadwal.edit', $jadwal->IdJadwal) }}'">
                        <div class="fw-bold">{{ $jadwal->dokter->Nama ?? '-' }}</div>
                        <div>{{ $jadwal->sesi }} ({{ $jadwal->sisa_kapasitas }}/{{ $jadwal->Kapasitas }})</div>
                    </div>
                @endforeach
            </div>

            @php $currentDay->addDay(); @endphp
        @endwhile
    </div>
</div>

<script>
    // Auto-dismiss alerts after 5 seconds
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
