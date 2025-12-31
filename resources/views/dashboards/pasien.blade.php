@extends('layouts.dashboard')

@section('title', 'Dashboard Pasien - Zenith Dental')
@section('header-title', 'Dashboard Pasien')
@section('header-subtitle', 'Pantau kesehatan gigi Anda dengan mudah')

@section('styles')
<style>
    /* Styling khusus untuk Dashboard Pasien agar lebih elegan */
    h1, h2, h3, h4, h5, h6 {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
    }

    .welcome-card {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        border-radius: 20px;
        color: white;
        position: relative;
        overflow: hidden;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.3);
    }

    .welcome-overlay {
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: radial-gradient(circle at 90% 10%, rgba(255,255,255,0.2) 0%, transparent 60%);
        pointer-events: none;
    }

    .doctor-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 20px;
        padding: 20px;
        transition: all 0.3s ease;
        min-width: 280px;
        position: relative;
    }

    .doctor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        border-color: rgba(14, 165, 233, 0.2);
    }

    .doctor-img-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
    }

    .doctor-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .menu-grid-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.04);
        height: 100%;
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
        overflow: hidden;
    }

    .menu-grid-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: rgba(14, 165, 233, 0.3);
    }
    
    .menu-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all 0.3s;
    }

    .menu-grid-card:hover .menu-icon {
        transform: scale(1.1) rotate(5deg);
    }

    /* Scrollbar halus untuk daftar dokter */
    .doctor-list-container {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
        padding-bottom: 20px;
        padding-top: 5px;
        padding-left: 5px; /* prevent shadow crop */
    }
    
    .promo-card {
        border-radius: 20px;
        overflow: hidden;
    }

    .article-item {
        transition: background 0.2s;
        border-bottom: 1px solid #f1f5f9;
    }
    .article-item:last-child {
        border-bottom: none;
    }
    .article-item:hover {
        background: #f8fafc;
    }
</style>
<!-- Font import same as welcome -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('sidebar-menu')
<a href="{{ route('pasien.dashboard') }}" class="nav-link {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-home"></i> Beranda</a>
<a href="{{ route('pasien.jadwal') }}" class="nav-link {{ request()->routeIs('pasien.jadwal') ? 'active' : '' }}"><i class="fa-solid fa-calendar-check"></i> Jadwal Saya</a>
<a href="{{ route('pasien.rekam-medis') }}" class="nav-link {{ request()->routeIs('pasien.rekam-medis') ? 'active' : '' }}"><i class="fa-solid fa-file-medical"></i> Rekam Medis</a>
<a href="{{ route('pasien.notifications') }}" class="nav-link {{ request()->routeIs('pasien.notifications') ? 'active' : '' }}"><i class="fa-solid fa-bell"></i> Notifikasi</a>
<a href="#" class="nav-link"><i class="fa-solid fa-gear"></i> Pengaturan</a>
@endsection

@section('content')

<!-- Welcome Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="welcome-card p-4 p-md-5">
            <div class="welcome-overlay"></div>
            <div class="row align-items-center position-relative z-1">
                <div class="col-md-7">
                    <span class="badge bg-white bg-opacity-20 backdrop-blur text-white mb-3 px-3 py-2 rounded-pill fw-light border border-white border-opacity-25">
                        <i class="fas fa-smile me-2"></i>Senyum Sehat Hari Ini
                    </span>
                    <h1 class="display-5 fw-bold mb-3">Halo, {{ Auth::user()->name ?? 'Pasien' }}! 👋</h1>
                    <p class="mb-4 lead text-white text-opacity-75" style="max-width: 500px;">
                        Kesehatan gigi adalah investasi masa depan. Jangan lupa jadwalkan pemeriksaan rutin Anda bersama dokter ahli kami.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('admin.booking.create') }}" class="btn btn-light text-primary fw-bold text-uppercase rounded-pill px-4 py-3 shadow-lg">
                            <i class="fa-solid fa-calendar-plus me-2"></i> Buat Janji Temu
                        </a>
                    </div>
                </div>
                <div class="col-md-5 d-none d-md-block text-center">
                    <div class="position-relative">
                        <i class="fa-solid fa-tooth text-white opacity-25" style="font-size: 12rem; transform: rotate(15deg);"></i>
                        <div class="position-absolute top-50 start-50 translate-middle bg-white bg-opacity-10 backdrop-blur rounded-3 p-3 border border-white border-opacity-20 shadow-lg text-start" style="width: 200px;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="bg-success rounded-circle p-1"><i class="fas fa-check text-white" style="font-size: 10px;"></i></div>
                                <span class="small fw-bold text-white">Jadwal Mendatang</span>
                            </div>
                            @if($upcomingBookings->count() > 0)
                                <div class="text-white small fw-bold">{{ $upcomingBookings->first()->jadwal->Tanggal }}</div>
                                <div class="text-white small opacity-75">{{ $upcomingBookings->first()->jadwal->JamMulai }} - {{ $upcomingBookings->first()->jadwal->dokter->Nama ?? 'Dokter' }}</div>
                            @else
                                <div class="text-white small opacity-75">Tidak ada jadwal</div>
                            @endif
                        </div>
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

        <!-- Recent Activity / History -->
        <h4 class="fw-bold mb-3">Riwayat Terakhir</h4>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            @forelse($medicalHistory as $history)
            <div class="article-item p-3 d-flex gap-3 align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                    <i class="fa-solid fa-file-medical-alt fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold mb-1 text-dark">{{ $history->tindakan->first()->NamaTindakan ?? 'Pemeriksaan' }}</h6>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($history->Tanggal)->format('d M Y') }}</small>
                    </div>
                    <small class="text-muted">Dokter: {{ $history->dokter->Nama ?? '-' }} • Keluhan: {{ Str::limit($history->Keluhan, 50) }}</small>
                </div>
            </div>
            @empty
            <div class="p-5 text-center text-muted">
                <i class="fa-solid fa-folder-open fa-3x mb-3 opacity-25"></i>
                <p>Belum ada riwayat kunjungan</p>
            </div>
            @endforelse
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
                    <div class="fw-bold text-primary">{{ \Carbon\Carbon::parse($booking->jadwal->Tanggal)->format('d') }}</div>
                    <div class="small text-muted">{{ \Carbon\Carbon::parse($booking->jadwal->Tanggal)->format('M') }}</div>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">{{ $booking->jadwal->dokter->Nama ?? 'Dokter' }}</h6>
                    <small class="text-muted d-block">{{ $booking->jadwal->JamMulai }} - {{ $booking->jadwal->JamSelesai }}</small>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 mt-1" style="font-size: 0.65rem;">Terkonfirmasi</span>
                </div>
            </div>
            @empty
            <div class="text-center py-3">
                <p class="text-muted small">Tidak ada janji temu aktif</p>
                <a href="{{ route('admin.booking.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">Buat Sekarang</a>
            </div>
            @endforelse
        </div>

        <!-- Quick Contact -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary text-white">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-white bg-opacity-20 rounded-circle p-2">
                    <i class="fa-solid fa-headset fa-xl"></i>
                </div>
                <div>
                    <h5 class="fw-bold m-0">Butuh Bantuan?</h5>
                    <small class="text-white text-opacity-75">Customer Service</small>
                </div>
            </div>
            <p class="small text-white text-opacity-75 mb-3">Tim kami siap membantu Anda menjadwalkan konsultasi atau menjawab pertanyaan.</p>
            <a href="https://wa.me/628123456789" target="_blank" class="btn btn-white text-primary w-100 fw-bold rounded-pill">Hubungi Kami</a>
        </div>
    </div>
</div>

@endsection
