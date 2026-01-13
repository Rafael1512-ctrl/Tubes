@extends('layouts.dashboard')

@section('title', 'Dashboard Pasien - Zenith Dental')
@section('no-sidebar', 'true')
@section('header-title', 'Dashboard Pasien')
@section('header-subtitle', 'Pantau kesehatan gigi Anda dengan mudah')

@section('navbar-menu')
<a href="{{ route('pasien.dashboard') }}" class="nav-link {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}">Beranda</a>
<a href="{{ route('pasien.jadwal') }}" class="nav-link {{ request()->routeIs('pasien.jadwal') ? 'active' : '' }}">Jadwal Saya</a>
<a href="{{ route('pasien.rekam-medis') }}" class="nav-link {{ request()->routeIs('pasien.rekam-medis') ? 'active' : '' }}">Rekam Medis</a>
@endsection

@section('styles')
<style>
    /* Align with Landing Page (welcome.blade.php) */
    :root {
        --primary: #0ea5e9;
        --secondary: #2563eb;
        --gradient-primary: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
    }

    .welcome-card {
        background: var(--gradient-primary);
        border-radius: 30px;
        color: white;
        position: relative;
        overflow: hidden;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(14, 165, 233, 0.25);
    }

    .welcome-overlay {
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: url('https://www.transparenttextures.com/patterns/cubes.png');
        opacity: 0.1;
        pointer-events: none;
    }

    .menu-grid-card {
        background: white;
        border-radius: 24px;
        padding: 1.75rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .menu-grid-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        border-color: var(--primary);
    }
    
    .menu-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s;
    }

    .stat-badge {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 100px;
        font-weight: 600;
        font-size: 0.8rem;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .article-item {
        transition: all 0.3s;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
    }

    .article-item:hover {
        background: #f8fafc;
        padding-left: 1.5rem !important;
    }

    .nav-link {
        font-weight: 600;
        color: #64748b;
        transition: all 0.3s;
        padding: 0.5rem 1rem !important;
        border-radius: 10px;
    }

    .nav-link:hover, .nav-link.active {
        color: var(--primary);
        background: rgba(14, 165, 233, 0.05);
    }

    /* Premium Illustrations Containers */
    .illustration-box {
        position: relative;
    }

    .floating-img {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
</style>
@endsection

@section('content')

<!-- Welcome Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="welcome-card p-4 p-md-5">
            <div class="welcome-overlay"></div>
            <div class="row align-items-center position-relative z-1">
                <div class="col-md-7 ps-md-4">
                    <div class="stat-badge mb-4 d-inline-block">
                        <i class="fas fa-sparkles me-2"></i>SENYUM SEHAT HARI INI
                    </div>
                    <h1 class="display-4 fw-bold mb-3">Halo, {{ Auth::user()->name ?? 'Pasien' }}! 👋</h1>
                    <p class="mb-4 lead text-white text-opacity-90 fw-light" style="max-width: 500px; line-height: 1.6;">
                        Kesehatan gigi adalah investasi masa depan. Jangan lupa jadwalkan pemeriksaan rutin Anda bersama dokter ahli kami.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('pasien.booking.create') }}" class="btn btn-light text-primary fw-bold rounded-pill px-5 py-3 shadow-lg transform-hover">
                            <i class="fa-solid fa-calendar-plus me-2"></i> Buat Janji Temu
                        </a>
                    </div>
                </div>
                <div class="col-md-5 d-none d-md-block text-center mt-4 mt-md-0">
                    <div class="illustration-box floating-img">
                         <img src="https://cdni.iconscout.com/illustration/premium/thumb/dentist-examining-patient-teeth-illustration-download-in-svg-png-gif-file-formats--medical-care-dentistry-doctor-treatment-pack-healthcare-illustrations-4735519.png" class="img-fluid" style="max-height: 300px; filter: drop-shadow(0 20px 30px rgba(0,0,0,0.2));">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Main Content Left -->
    <div class="col-lg-8">
        <!-- Broadcasts Section -->
        @if($broadcasts->count() > 0)
        <h4 class="fw-bold mb-3">Pengumuman Terbaru</h4>
        @foreach($broadcasts as $bc)
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-3" style="background: #eff6ff; border-left: 5px solid #2563eb !important;">
            <div class="d-flex justify-content-between">
                <h5 class="fw-bold text-primary mb-1">{{ $bc->Title }}</h5>
                <small class="text-muted">{{ $bc->created_at->diffForHumans() }}</small>
            </div>
            <p class="mb-0 text-dark small">{{ $bc->Message }}</p>
        </div>
        @endforeach
        @endif

        <!-- Quick Menu Grid -->
        <h4 class="fw-bold mb-3">Menu Cepat</h4>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <a href="{{ route('pasien.rekam-medis') }}" class="text-decoration-none">
                    <div class="menu-grid-card">
                        <div class="menu-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fa-solid fa-notes-medical"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Riwayat Medis</h5>
                            <p class="text-muted m-0 small">Lihat catatan kunjungan & diagnosis</p>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto text-muted opacity-50"></i>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                 <a href="{{ route('pasien.jadwal') }}" class="text-decoration-none">
                    <div class="menu-grid-card">
                        <div class="menu-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Jadwal & Booking</h5>
                            <p class="text-muted m-0 small">Cek status janji temu Anda</p>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto text-muted opacity-50"></i>
                    </div>
                 </a>
            </div>
        </div>

    </div>

    <!-- Right Sidebar -->
    <div class="col-lg-4">
        <!-- Upcoming Booking Widget -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <h5 class="fw-bold mb-3">Janji Temu Mendatang</h5>
            @forelse($upcomingBookings as $booking)
            <div class="d-flex gap-3 mb-3 p-3 rounded-3 border">
                <div class="text-center bg-light px-2 py-1 rounded-2" style="min-width: 60px;">
                    <div class="fw-bold text-primary">{{ $booking->jadwal->Tanggal ? $booking->jadwal->Tanggal->format('d') : '-' }}</div>
                    <div class="small text-muted">{{ $booking->jadwal->Tanggal ? $booking->jadwal->Tanggal->format('M') : '-' }}</div>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">{{ $booking->jadwal->dokter->Nama ?? 'Dokter' }}</h6>
                    <small class="text-muted d-block">
                        {{ $booking->jadwal->JamMulai ? $booking->jadwal->JamMulai->format('H:i') : '-' }} - 
                        {{ $booking->jadwal->JamAkhir ? $booking->jadwal->JamAkhir->format('H:i') : '-' }}
                    </small>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 mt-1" style="font-size: 0.65rem;">Terkonfirmasi</span>
                </div>
            </div>
            @empty
            <div class="text-center py-3">
                <p class="text-muted small">Tidak ada janji temu aktif</p>
                <a href="{{ route('pasien.booking.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">Buat Sekarang</a>
            </div>
            @endforelse
        </div>


    </div>
</div>

<!-- Recent Activity / History (Full Width) -->
<div class="row mb-5">
    <div class="col-12">
        <h4 class="fw-bold mb-4">Riwayat Terakhir</h4>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            @forelse($medicalHistory as $history)
            <div class="article-item p-4 d-flex gap-4 align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-4 text-primary">
                    <i class="fa-solid fa-file-medical-alt fa-2xl"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold mb-0 text-dark">{{ $history->tindakan->first()->NamaTindakan ?? 'Pemeriksaan' }}</h5>
                        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
                            <i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($history->Tanggal)->format('d M Y') }}
                        </span>
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="text-muted small"><i class="fa-solid fa-user-doctor me-1 text-primary"></i> Dokter: {{ $history->dokter->Nama ?? '-' }}</span>
                        <span class="text-muted small"><i class="fa-solid fa-notes me-1 text-primary"></i> Keluhan: {{ $history->Keluhan ?? '-' }}</span>
                    </div>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('pasien.rekam-medis') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">Detail</a>
                </div>
            </div>
            @empty
            <div class="p-5 text-center text-muted">
                <i class="fa-solid fa-folder-open fa-3x mb-3 opacity-25"></i>
                <p>Belum ada riwayat kunjungan</p>
                <a href="{{ route('pasien.booking.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">Buat Janji Pertama</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Floating WhatsApp -->
<a href="https://wa.me/62895360828717" class="whatsapp-float" target="_blank" aria-label="Hubungi kami di WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>
<div class="whatsapp-tooltip">Butuh bantuan? Chat kami!</div>

@endsection
