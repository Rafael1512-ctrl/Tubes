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

    /* Floating WhatsApp Button */
    .whatsapp-float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 30px;
        right: 30px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 32px;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
    }

    .whatsapp-float:hover {
        transform: scale(1.1) rotate(5deg);
        color: white;
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
    }

    .whatsapp-tooltip {
        position: fixed;
        bottom: 42px;
        right: 100px;
        background: white;
        color: #1a1a1a;
        padding: 8px 15px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        font-size: 14px;
        font-weight: 600;
        z-index: 999;
        pointer-events: none;
        opacity: 0;
        transform: translateX(10px);
        transition: all 0.3s ease;
    }

    .whatsapp-float:hover + .whatsapp-tooltip {
        opacity: 1;
        transform: translateX(0);
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
@endsection

@section('content')

<!-- Welcome Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="welcome-card p-4 p-md-5">
            <div class="welcome-overlay"></div>
            <div class="row align-items-center position-relative z-1">
                <div class="col-md-7 ps-md-4">
                    <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 backdrop-blur text-white px-3 py-2 rounded-pill mb-4 border border-white border-opacity-20 shadow-sm">
                        <span class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 24px; height: 24px; font-size: 12px;">
                            <i class="fas fa-sparkles"></i>
                        </span>
                        <span class="small fw-semibold letter-spacing-1">SENYUM SEHAT HARI INI</span>
                    </div>
                    <h1 class="display-5 fw-bold mb-3">Halo, {{ Auth::user()->name ?? 'Pasien' }}! 👋</h1>
                    <p class="mb-4 lead text-white text-opacity-90 fw-light" style="max-width: 500px; line-height: 1.6;">
                        Kesehatan gigi adalah investasi masa depan. Jangan lupa jadwalkan pemeriksaan rutin Anda bersama dokter ahli kami.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('pasien.booking.create') }}" class="btn btn-light text-primary fw-bold text-uppercase rounded-pill px-4 py-3 shadow-lg hover-scale">
                            <i class="fa-solid fa-calendar-plus me-2"></i> Buat Janji Temu
                        </a>
                    </div>
                </div>
                <div class="col-md-5 d-none d-md-block text-center position-relative">
                     <i class="fa-solid fa-tooth text-white opacity-10 position-absolute" style="font-size: 15rem; transform: rotate(15deg); top: -20px; right: -20px; z-index: 0;"></i>
                    <div class="position-relative z-1 d-inline-block">
                        <div class="bg-white rounded-4 p-4 shadow-lg text-start" style="width: 280px; transform: rotate(-2deg); transition: transform 0.3s ease;">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fa-solid fa-calendar-check text-primary"></i>
                                    </div>
                                    <span class="small fw-bold text-dark text-uppercase tracking-wider">Jadwal Mendatang</span>
                                </div>
                                <div class="spinner-grow spinner-grow-sm text-primary opacity-50" role="status"></div>
                            </div>
                            @if($upcomingBookings->count() > 0)
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="bg-primary rounded-3 text-center px-3 py-2 text-white">
                                         <span class="d-block fw-bold display-6 mb-0" style="line-height: 1;">
                                            {{ $upcomingBookings->first()->jadwal->Tanggal ? $upcomingBookings->first()->jadwal->Tanggal->format('d') : '-' }}
                                         </span>
                                         <span class="d-block small text-white text-opacity-75 text-uppercase" style="font-size: 0.7rem;">
                                            {{ $upcomingBookings->first()->jadwal->Tanggal ? $upcomingBookings->first()->jadwal->Tanggal->format('M') : '-' }}
                                         </span>
                                    </div>
                                    <div>
                                        <div class="text-dark fw-bold mb-0" style="font-size: 1rem;">{{ $upcomingBookings->first()->jadwal->dokter->Nama ?? 'Dokter Gigi' }}</div>
                                        <div class="text-muted small">
                                            <i class="fa-regular fa-clock me-1"></i> 
                                            {{ $upcomingBookings->first()->jadwal->JamMulai ? $upcomingBookings->first()->jadwal->JamMulai->format('H:i') : '-' }} WIB
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center gap-2 text-muted py-2">
                                    <i class="fa-regular fa-calendar-xmark fa-lg"></i>
                                    <span class="small">Belum ada jadwal aktif. <br>Yuk buat janji sekarang!</span>
                                </div>
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

<!-- Floating WhatsApp -->
<a href="https://wa.me/62895360828717" class="whatsapp-float" target="_blank" aria-label="Hubungi kami di WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>
<div class="whatsapp-tooltip">Butuh bantuan? Chat kami!</div>

@endsection
