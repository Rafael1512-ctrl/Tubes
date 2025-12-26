@extends('layouts.dashboard')

@section('title', 'Dashboard Pasien - Zenith Dental')
@section('header-title', 'Dashboard Pasien')
@section('header-subtitle', 'Pantau kesehatan gigi Anda dengan mudah')

@section('sidebar-menu')
<a href="#" class="nav-link active"><i class="fa-solid fa-home"></i> Beranda</a>
<a href="#" class="nav-link"><i class="fa-solid fa-calendar-check"></i> Jadwal Saya</a>
<a href="#" class="nav-link"><i class="fa-solid fa-file-medical"></i> Rekam Medis</a>
<a href="#" class="nav-link"><i class="fa-solid fa-envelope"></i> Pesan Masuk</a>
<a href="#" class="nav-link"><i class="fa-solid fa-gear"></i> Pengaturan</a>
@endsection

@section('content')

<!-- Hero / Welcome -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-custom bg-primary text-white" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2">Halo, {{ Auth::user()->name ?? 'Pasien' }}! 👋</h2>
                    <p class="mb-4 opacity-75">Jangan lupa jaga kesehatan gigi Anda hari ini. Jadwalkan pemeriksaan rutin sekarang.</p>
                    <button class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                        <i class="fa-solid fa-plus-circle me-2"></i> Buat Janji Baru
                    </button>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <img src="{{ asset('images/logo.png') }}" class="img-fluid opacity-50" style="max-height: 150px; filter: invert(1);">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pilih Dokter (Horizontal Scroll) -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold">Pilih Dokter Spesialis</h5>
        <a href="#" class="text-decoration-none small fw-bold">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    
    <div class="d-flex gap-3 overflow-auto pb-3" style="scrollbar-width: thin;">
        <!-- Card 1 -->
        <div class="card-custom border-0 shadow-sm" style="min-width: 280px;">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="https://ui-avatars.com/api/?name=Dr+Budi&background=e0f2fe&color=2563eb" class="rounded-circle" width="60">
                <div>
                    <h6 class="fw-bold mb-0">drg. Budi Santoso</h6>
                    <small class="text-muted">Spesialis Orthodonti</small>
                </div>
            </div>
            <div class="d-flex gap-2 mb-3">
                <span class="badge bg-light text-primary"><i class="fa-solid fa-star text-warning"></i> 4.9</span>
                <span class="badge bg-light text-secondary">5th Pengalaman</span>
            </div>
            <button class="btn btn-outline-primary w-100 rounded-pill btn-sm">Lihat Jadwal</button>
        </div>

        <!-- Card 2 -->
        <div class="card-custom border-0 shadow-sm" style="min-width: 280px;">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="https://ui-avatars.com/api/?name=Dr+Sari&background=fce7f3&color=db2777" class="rounded-circle" width="60">
                <div>
                    <h6 class="fw-bold mb-0">drg. Sari Mawar</h6>
                    <small class="text-muted">Dokter Gigi Anak</small>
                </div>
            </div>
            <div class="d-flex gap-2 mb-3">
                <span class="badge bg-light text-pink"><i class="fa-solid fa-star text-warning"></i> 5.0</span>
                <span class="badge bg-light text-secondary">8th Pengalaman</span>
            </div>
            <button class="btn btn-outline-primary w-100 rounded-pill btn-sm">Lihat Jadwal</button>
        </div>

        <!-- Card 3 -->
        <div class="card-custom border-0 shadow-sm" style="min-width: 280px;">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="https://ui-avatars.com/api/?name=Dr+Andi&background=dcfce7&color=16a34a" class="rounded-circle" width="60">
                <div>
                    <h6 class="fw-bold mb-0">drg. Andi Wijaya</h6>
                    <small class="text-muted">Bedah Mulut</small>
                </div>
            </div>
            <div class="d-flex gap-2 mb-3">
                <span class="badge bg-light text-success"><i class="fa-solid fa-star text-warning"></i> 4.8</span>
                <span class="badge bg-light text-secondary">12th Pengalaman</span>
            </div>
            <button class="btn btn-outline-primary w-100 rounded-pill btn-sm">Lihat Jadwal</button>
        </div>
        
         <!-- Card 4 -->
         <div class="card-custom border-0 shadow-sm" style="min-width: 280px;">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="https://ui-avatars.com/api/?name=Dr+Rina&background=ede9fe&color=7c3aed" class="rounded-circle" width="60">
                <div>
                    <h6 class="fw-bold mb-0">drg. Rina</h6>
                    <small class="text-muted">Konservasi Gigi</small>
                </div>
            </div>
             <div class="d-flex gap-2 mb-3">
                <span class="badge bg-light text-success"><i class="fa-solid fa-star text-warning"></i> 4.7</span>
                <span class="badge bg-light text-secondary">4th Pengalaman</span>
            </div>
            <button class="btn btn-outline-primary w-100 rounded-pill btn-sm">Lihat Jadwal</button>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Quick Actions & Articles (Left Column) -->
    <div class="col-lg-8">
        
        <!-- Tools Grid -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card-custom h-100 d-flex align-items-center gap-3 p-3">
                    <div class="bg-primary-subtle p-3 rounded-circle text-primary">
                        <i class="fa-solid fa-notes-medical fa-xl"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Riwayat Medis</h6>
                        <small class="text-muted">Lihat catatan pemeriksaan lalu</small>
                    </div>
                    <a href="#" class="stretched-link"></a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom h-100 d-flex align-items-center gap-3 p-3">
                    <div class="bg-warning-subtle p-3 rounded-circle text-warning">
                        <i class="fa-solid fa-envelope-open-text fa-xl"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Mailbox</h6>
                        <small class="text-muted">Pesan dari dokter & admin</small>
                    </div>
                     <a href="#" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Video Education -->
        <div class="card-custom mb-4 p-0 overflow-hidden">
            <div class="row g-0">
                <div class="col-md-5 position-relative">
                   <img src="{{ asset('images/education_thumbnail.png') }}" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 200px;">
                   <a href="https://www.youtube.com/watch?v=depO-k2e6_4" target="_blank" class="position-absolute top-50 start-50 translate-middle bg-white rounded-circle p-3 shadow text-danger">
                       <i class="fa-solid fa-play fa-xl"></i>
                   </a>
                </div>
                <div class="col-md-7 p-4 d-flex flex-column justify-content-center">
                    <span class="badge bg-info bg-opacity-10 text-info w-auto mb-2 align-self-start">Edukasi</span>
                    <h5 class="fw-bold">Cara Menyikat Gigi yang Benar</h5>
                    <p class="text-muted small mb-3">Pelajari teknik menyikat gigi yang direkomendasikan dokter untuk mencegah gigi berlubang.</p>
                    <a href="#" class="text-primary fw-bold text-decoration-none small">Tonton Video <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Articles List -->
        <h5 class="fw-bold mb-3">Artikel Kesehatan Terbaru</h5>
        <div class="card-custom p-0">
             <div class="list-group list-group-flush rounded-4">
                <a href="#" class="list-group-item list-group-item-action p-3 d-flex gap-3 align-items-center">
                    <img src="https://source.unsplash.com/100x100/?teeth" class="rounded-3" width="60" height="60" style="object-fit: cover">
                    <div>
                        <h6 class="fw-bold mb-1">Mengenal Scaling Gigi dan Manfaatnya</h6>
                        <small class="text-muted">Diposting 2 hari yang lalu oleh drg. Budi</small>
                    </div>
                </a>
                <a href="#" class="list-group-item list-group-item-action p-3 d-flex gap-3 align-items-center">
                    <img src="https://source.unsplash.com/100x100/?fruit" class="rounded-3" width="60" height="60" style="object-fit: cover">
                    <div>
                        <h6 class="fw-bold mb-1">Makanan yang Baik untuk Kesehatan Gigi</h6>
                        <small class="text-muted">Diposting 5 hari yang lalu oleh Admin</small>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <!-- Right Sidebar (Promo & Certificates) -->
    <div class="col-lg-4">
        
        <!-- Promo Card -->
        <div class="card-custom p-0 mb-4 overflow-hidden position-relative border-0 shadow">
            <img src="{{ asset('images/promo_whitening.png') }}" class="w-100" alt="Promo">
            <div class="p-3 bg-white">
                <h6 class="fw-bold text-primary mb-1">Spesial Bulan Ini!</h6>
                <p class="small text-muted mb-3">Dapatkan diskon 50% untuk teeth whitening.</p>
                <button class="btn btn-dark w-100 btn-sm rounded-pill">Ambil Promo</button>
            </div>
        </div>

        <!-- Certificates -->
        <div class="card-custom mb-4">
            <h6 class="fw-bold mb-3">Lisensi & Sertifikasi</h6>
            <div class="text-center">
                <img src="{{ asset('images/certificate_iso.png') }}" class="img-fluid rounded shadow-sm mb-2" alt="ISO Certificate">
                <small class="text-muted d-block">Terakreditasi ISO 9001:2015</small>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="card-custom bg-body-secondary border-0">
            <h6 class="fw-bold mb-3">Apa Kata Mereka?</h6>
            <div id="testimoniCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <p class="small fst-italic mb-2">"Dokternya ramah banget, klinik bersih dan modern. Sangat puas!"</p>
                        <div class="d-flex align-items-center gap-2">
                             <img src="https://ui-avatars.com/api/?name=Siti&background=random" class="rounded-circle" width="30">
                             <small class="fw-bold">Siti Aminah</small>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <p class="small fst-italic mb-2">"Anak saya jadi gak takut ke dokter gigi lagi. Terima kasih Zenith Dental!"</p>
                        <div class="d-flex align-items-center gap-2">
                             <img src="https://ui-avatars.com/api/?name=Budi&background=random" class="rounded-circle" width="30">
                             <small class="fw-bold">Pak Budi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
