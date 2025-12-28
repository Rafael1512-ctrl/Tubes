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
<a href="#" class="nav-link active"><i class="fa-solid fa-home"></i> Beranda</a>
<a href="#" class="nav-link"><i class="fa-solid fa-calendar-check"></i> Jadwal Saya</a>
<a href="#" class="nav-link"><i class="fa-solid fa-file-medical"></i> Rekam Medis</a>
<a href="#" class="nav-link"><i class="fa-solid fa-envelope"></i> Pesan Masuk</a>
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
                        <button class="btn btn-light text-primary fw-bold text-uppercase rounded-pill px-4 py-3 shadow-lg">
                            <i class="fa-solid fa-calendar-plus me-2"></i> Buat Janji Temu
                        </button>
                    </div>
                </div>
                <div class="col-md-5 d-none d-md-block text-center">
                    <!-- Illustrative visual -->
                    <div class="position-relative">
                        <i class="fa-solid fa-tooth text-white opacity-25" style="font-size: 12rem; transform: rotate(15deg);"></i>
                        <div class="position-absolute top-50 start-50 translate-middle bg-white bg-opacity-10 backdrop-blur rounded-3 p-3 border border-white border-opacity-20 shadow-lg text-start" style="width: 200px;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="bg-success rounded-circle p-1"><i class="fas fa-check text-white" style="font-size: 10px;"></i></div>
                                <span class="small fw-bold text-white">Jadwal Aktif</span>
                            </div>
                            <div class="text-white small opacity-75">No Appointment</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dokter & Jadwal -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4 px-1">
        <div>
            <h4 class="fw-bold mb-1">Dokter Spesialis Kami</h4>
            <p class="text-muted m-0 small">Pilih dokter terbaik untuk perawatan gigi Anda</p>
        </div>
        <a href="#" class="btn btn-outline-primary rounded-pill btn-sm px-3 fw-bold">
            Lihat Semua <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>
    
    <div class="d-flex gap-4 overflow-auto doctor-list-container">
        <!-- Doctor Card 1 -->
        <div class="doctor-card shadow-sm">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="doctor-img-wrapper">
                    <img src="https://ui-avatars.com/api/?name=Dr+Budi&background=e0f2fe&color=0ea5e9" alt="Dr Budi">
                </div>
                <div>
                    <h5 class="fw-bold mb-1">drg. Budi Santoso</h5>
                    <div class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2">Orthodonti</div>
                </div>
            </div>
            <div class="d-flex justify-content-between text-center mb-4 border-top border-bottom py-3">
                <div>
                    <div class="fw-bold fs-5 text-dark">4.9</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Rating</div>
                </div>
                <div>
                    <div class="fw-bold fs-5 text-dark">5th</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Pengalaman</div>
                </div>
                <div>
                    <div class="fw-bold fs-5 text-dark">1k+</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Pasien</div>
                </div>
            </div>
            <button class="btn btn-primary w-100 rounded-pill py-2 fw-semibold shadow-sm text-white">
                Lihat Jadwal
            </button>
        </div>

        <!-- Doctor Card 2 -->
        <div class="doctor-card shadow-sm">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="doctor-img-wrapper">
                    <img src="https://ui-avatars.com/api/?name=Dr+Sari&background=fce7f3&color=db2777" alt="Dr Sari">
                </div>
                <div>
                    <h5 class="fw-bold mb-1">drg. Sari Mawar</h5>
                    <div class="badge bg-pink bg-opacity-10 text-danger rounded-pill px-2">Gigi Anak</div>
                </div>
            </div>
            <div class="d-flex justify-content-between text-center mb-4 border-top border-bottom py-3">
                <div>
                    <div class="fw-bold fs-5 text-dark">5.0</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Rating</div>
                </div>
                <div>
                    <div class="fw-bold fs-5 text-dark">8th</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Pengalaman</div>
                </div>
                <div>
                    <div class="fw-bold fs-5 text-dark">2k+</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Pasien</div>
                </div>
            </div>
            <button class="btn btn-outline-primary w-100 rounded-pill py-2 fw-semibold shadow-sm">
                Lihat Jadwal
            </button>
        </div>
        
        <!-- Doctor Card 3 -->
        <div class="doctor-card shadow-sm">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="doctor-img-wrapper">
                    <img src="https://ui-avatars.com/api/?name=Dr+Andi&background=dcfce7&color=16a34a" alt="Dr Andi">
                </div>
                <div>
                    <h5 class="fw-bold mb-1">drg. Andi Wijaya</h5>
                    <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Bedah Mulut</div>
                </div>
            </div>
            <div class="d-flex justify-content-between text-center mb-4 border-top border-bottom py-3">
                <div>
                    <div class="fw-bold fs-5 text-dark">4.8</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Rating</div>
                </div>
                <div>
                    <div class="fw-bold fs-5 text-dark">12th</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Pengalaman</div>
                </div>
                <div>
                    <div class="fw-bold fs-5 text-dark">3k+</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Pasien</div>
                </div>
            </div>
            <button class="btn btn-outline-primary w-100 rounded-pill py-2 fw-semibold shadow-sm">
                Lihat Jadwal
            </button>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Main Content Left -->
    <div class="col-lg-8">
        <!-- Quick Menu Grid -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <a href="#" class="text-decoration-none">
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
                 <a href="#" class="text-decoration-none">
                    <div class="menu-grid-card">
                        <div class="menu-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Tagihan & Pembayaran</h5>
                            <p class="text-muted m-0 small">Cek invoice perawatan</p>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto text-muted opacity-50"></i>
                    </div>
                 </a>
            </div>
        </div>

        <!-- Article Section -->
        <h4 class="fw-bold mb-3">Informasi Kesehatan</h4>
        <div class="card bg-white border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="article-item p-3 d-flex gap-3 align-items-center cursor-pointer">
                <img src="https://source.unsplash.com/100x100/?dentist" class="rounded-3" width="80" height="80" style="object-fit: cover">
                <div class="flex-grow-1">
                    <div class="badge bg-light text-secondary mb-1">Tips</div>
                    <h6 class="fw-bold mb-1 text-dark">Cara Merawat Gigi Berlubang di Rumah</h6>
                    <small class="text-muted">Dr. Sarah • 5 menit baca</small>
                </div>
                <i class="fa-solid fa-chevron-right text-muted"></i>
            </div>
             <div class="article-item p-3 d-flex gap-3 align-items-center cursor-pointer">
                <img src="https://source.unsplash.com/100x100/?fruit" class="rounded-3" width="80" height="80" style="object-fit: cover">
                <div class="flex-grow-1">
                    <div class="badge bg-light text-secondary mb-1">Nutrisi</div>
                    <h6 class="fw-bold mb-1 text-dark">Makanan Terbaik untuk Kesehatan Gusi</h6>
                    <small class="text-muted">Admin • 3 menit baca</small>
                </div>
                <i class="fa-solid fa-chevron-right text-muted"></i>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="col-lg-4">
        <!-- Promo Banner -->
        @if(file_exists(public_path('images/promo_whitening.png')))
        <div class="promo-card mb-4 position-relative shadow-sm group-hover-zoom">
            <img src="{{ asset('images/promo_whitening.png') }}" class="w-100" alt="Promo">
            <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                <span class="badge bg-warning text-dark mb-1">Promo Terbatas</span>
                <h5 class="text-white fw-bold m-0">Diskon 50% Whitening</h5>
            </div>
        </div>
        @else
        <div class="card bg-dark text-white rounded-4 p-4 mb-4 text-center">
            <h4 class="fw-bold text-warning">Promo Spesial!</h4>
            <p>Dapatkan penawaran menarik setiap bulannya.</p>
        </div>
        @endif
        
        <!-- Quick Contact -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary text-white">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-white bg-opacity-20 rounded-circle p-2">
                    <i class="fa-solid fa-headset fa-xl"></i>
                </div>
                <div>
                    <h5 class="fw-bold m-0">Butuh Bantuan?</h5>
                    <small class="text-white text-opacity-75">Customer Service 24/7</small>
                </div>
            </div>
            <p class="small text-white text-opacity-75 mb-3">Tim kami siap membantu Anda menjadwalkan konsultasi atau menjawab pertanyaan.</p>
            <button class="btn btn-white text-primary w-100 fw-bold rounded-pill">Hubungi Kami</button>
        </div>
    </div>
</div>

@endsection
