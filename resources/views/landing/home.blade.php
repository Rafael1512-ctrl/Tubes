@extends('layouts.app_landing')

@section('title', 'Klinik Gigi Estetika Terbaik Bandung')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-white d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-light text-primary mb-3 px-3 py-2 rounded-pill fw-bold ls-1">Klinik Gigi Estetika Bandung</span>
                <h1 class="display-3 fw-bold mb-4 leading-tight">Wujudkan Senyum Impian dengan Sentuhan Profesional</h1>
                <p class="lead mb-5 opacity-90 me-lg-5">BrightSmile Care menawarkan perawatan estetika gigi modern dengan teknologi terkini dan dokter berpengalaman. Percayakan senyum indah Anda pada kami.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('landing.booking') }}" class="btn btn-light text-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg hover-scale">Booking Sekarang</a>
                    <a href="#services" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill fw-bold hover-scale">Lihat Layanan</a>
                </div>
                <div class="mt-5 d-flex gap-5">
                    <div class="text-center">
                        <h3 class="fw-bold mb-0">15+</h3>
                        <small class="text-white-50">Tahun Pengalaman</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold mb-0">5k+</h3>
                        <small class="text-white-50">Pasien Puas</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block position-relative">
                 <!-- Image placeholder or CSS illustration -->
                 <div class="hero-image-blob">
                    <!-- Ideally an image here -->
                 </div>
            </div>
        </div>
    </div>
</section>

<!-- Features / Intro -->
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card p-4 rounded-3 h-100 border-0 shadow-sm hover-up text-center text-md-start">
                    <div class="icon-circle bg-blue-10 mb-4 text-primary">
                        <i class="fas fa-certificate fa-2x"></i>
                    </div>
                    <h4>Dokter Tersertifikasi</h4>
                    <p class="text-muted">Tim dokter gigi spesialis estetika dengan sertifikasi nasional dan pengalaman bertahun-tahun.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4 rounded-3 h-100 border-0 shadow-sm hover-up text-center text-md-start">
                    <div class="icon-circle bg-blue-10 mb-4 text-primary">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                    <h4>Teknologi Modern</h4>
                    <p class="text-muted">Menggunakan peralatan medis berstandar internasional untuk hasil yang presisi dan maksimal.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4 rounded-3 h-100 border-0 shadow-sm hover-up text-center text-md-start">
                    <div class="icon-circle bg-blue-10 mb-4 text-primary">
                        <i class="fas fa-heart fa-2x"></i>
                    </div>
                    <h4>Kenyamanan Pasien</h4>
                    <p class="text-muted">Suasana klinik yang homey, bersih, dan pelayanan ramah untuk pengalaman yang menenangkan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Layanan Unggulan -->
<section id="services" class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5 mw-700 mx-auto">
            <h6 class="text-primary fw-bold text-uppercase ls-2">Layanan Kami</h6>
            <h2 class="display-5 fw-bold mb-3">Solusi Estetika Gigi Terlengkap</h2>
            <p class="text-muted">Pilih perawatan yang sesuai dengan kebutuhan impian senyum Anda.</p>
        </div>

        <div class="row g-4">
            <!-- Service 1: Veneer -->
            <div class="col-md-4">
                <div class="card service-card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-img-top bg-secondary p-5 text-center text-white d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(45deg, #1E88E5, #42A5F5);">
                        <i class="fas fa-teeth-open fa-4x"></i>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold">Porcelain Veneer</h4>
                        <p class="card-text text-muted">Lapisan porselen tipis untuk mengoreksi bentuk, warna, dan posisi gigi secara instan dan permanen.</p>
                        <ul class="list-unstyled text-muted small mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Hasil natural & putih</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Tahan hingga 15 tahun</li>
                            <li><i class="fas fa-check text-success me-2"></i> Custom shape design</li>
                        </ul>
                        <a href="#" class="btn btn-outline-primary w-100 rounded-pill">Detail Layanan</a>
                    </div>
                </div>
            </div>

            <!-- Service 2: Whitening -->
            <div class="col-md-4">
                <div class="card service-card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-img-top bg-secondary p-5 text-center text-white d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(45deg, #0D47A1, #1976D2);">
                        <i class="fas fa-magic fa-4x"></i>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold">Teeth Whitening</h4>
                        <p class="card-text text-muted">Memutihkan gigi hingga 8 tingkat lebih cerah hanya dalam satu kali kunjungan 60 menit.</p>
                        <ul class="list-unstyled text-muted small mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Aman & Tanpa rasa sakit</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Teknik Laser Modern</li>
                            <li><i class="fas fa-check text-success me-2"></i> Hasil instan</li>
                        </ul>
                        <a href="#" class="btn btn-outline-primary w-100 rounded-pill">Detail Layanan</a>
                    </div>
                </div>
            </div>

             <!-- Service 3: Ortho -->
             <div class="col-md-4">
                <div class="card service-card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-img-top bg-secondary p-5 text-center text-white d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(45deg, #00ACC1, #26C6DA);">
                        <i class="fas fa-smile fa-4x"></i>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold">Invisalign & Braces</h4>
                        <p class="card-text text-muted">Merapikan gigi dengan pilihan kawat gigi estetik atau aligner bening yang tidak terlihat.</p>
                        <ul class="list-unstyled text-muted small mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Konsultasi digital scan</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Opsi transparan</li>
                            <li><i class="fas fa-check text-success me-2"></i> Cicilan tersedia</li>
                        </ul>
                        <a href="#" class="btn btn-outline-primary w-100 rounded-pill">Detail Layanan</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-5">
            <a href="#" class="btn btn-link text-decoration-none fw-bold fs-5">Lihat Semua Layanan <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>

<!-- CTA Booking -->
<section class="py-5 text-white position-relative overflow-hidden" style="background-color: #0D47A1;">
    <div class="container py-5 text-center position-relative" style="z-index: 2;">
        <h2 class="display-5 fw-bold mb-4">Mulai Perjalanan Senyum Indahmu Hari Ini</h2>
        <p class="lead mb-4 opacity-75 mw-600 mx-auto">Jangan tunda lagi untuk mendapatkan kepercayaan diri yang Anda impikan. Jadwalkan konsultasi gratis dengan dokter kami.</p>
        <a href="{{ route('landing.booking') }}" class="btn btn-light text-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg hover-scale">Booking Jadwal Online</a>
    </div>
    <!-- Decor element -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
</section>
@endsection
