<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Gigi Sehat - Senyum Sempurna Dimulai Di Sini</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0ea5e9; /* Sky Blue */
            --primary-dark: #0284c7;
            --secondary: #2dd4bf; /* Teal */
            --accent: #f59e0b;
            --dark: #0f172a;
            --light: #f8fafc;
            --surface: #ffffff;
            --glass: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.4);
            --gradient-primary: linear-gradient(135deg, #0ea5e9 0%, #2dd4bf 100%);
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-glow: 0 0 20px rgba(14, 165, 233, 0.3);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--dark);
            background: var(--light);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--dark);
        }

        /* Modern Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 20px 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled {
            padding: 15px 0;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 26px;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            object-fit: contain;
            /* Placeholder styles if logo image missing */
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-primary);
            color: white;
            font-size: 20px;
        }
        
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .nav-link {
            font-weight: 600;
            color: #64748b !important;
            margin: 0 15px;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--gradient-primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-primary-gradient {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        .btn-primary-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #2dd4bf 0%, #0ea5e9 100%);
            z-index: -1;
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        .btn-primary-gradient:hover::before {
            opacity: 1;
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.4);
            color: white;
        }

        /* Hero Section */
        .hero-section {
            padding: 180px 0 100px;
            background: 
                radial-gradient(circle at 10% 20%, rgba(45, 212, 191, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(14, 165, 233, 0.1) 0%, transparent 40%);
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            line-height: 1.1;
            margin-bottom: 25px;
            background: linear-gradient(to right, var(--dark) 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #64748b;
            margin-bottom: 40px;
            max-width: 90%;
            font-weight: 400;
        }

        .hero-image-wrapper {
            position: relative;
            z-index: 1;
        }

        .hero-img-main {
            border-radius: 30px;
            box-shadow: var(--shadow-lg);
            position: relative;
            z-index: 2;
            width: 100%;
            transform: perspective(1000px) rotateY(-5deg);
            transition: transform 0.5s ease;
        }

        .hero-image-wrapper:hover .hero-img-main {
            transform: perspective(1000px) rotateY(0deg);
        }

        .hero-floating-card {
            position: absolute;
            background: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: float 4s ease-in-out infinite;
        }

        .card-1 {
            bottom: 40px;
            left: -30px;
            min-width: 200px;
        }

        .card-2 {
            top: 40px;
            right: -30px;
            animation-delay: 2s;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* Features/Stats */
        .stats-section {
            margin-top: -50px;
            padding-bottom: 80px;
            position: relative;
            z-index: 5;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            height: 100%;
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(14, 165, 233, 0.15);
            border-color: rgba(14, 165, 233, 0.2);
        }

        .stat-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
            background: rgba(14, 165, 233, 0.1);
            color: var(--primary);
        }

        /* Services */
        .section-header {
            text-align: center;
            margin-bottom: 70px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-header .badge {
            background: rgba(14, 165, 233, 0.1);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: 600;
            margin-bottom: 15px;
            display: inline-block;
        }

        .service-card {
            background: white;
            border-radius: 24px;
            padding: 40px 30px;
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-icon {
            width: 70px;
            height: 70px;
            background: var(--light);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--primary);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            background: var(--gradient-primary);
            color: white;
        }

        /* Gallery/About */
        .about-section {
            padding: 100px 0;
            background: white;
        }

        .about-img-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            grid-template-rows: 200px 200px;
            gap: 20px;
        }

        .about-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
        }

        .about-img-1 { grid-row: 1 / 3; }
        .about-img-2 { grid-row: 1 / 2; }
        .about-img-3 { grid-row: 2 / 3; }

        /* CTA */
        .cta-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .cta-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.5;
        }

        /* Footer */
        .footer {
            background: #f8fafc;
            padding-top: 80px;
            border-top: 1px solid #e2e8f0;
        }

        .footer h5 {
            color: var(--dark);
            margin-bottom: 25px;
            font-weight: 700;
        }

        .footer-link {
            display: block;
            color: #64748b;
            margin-bottom: 12px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .footer-link:hover {
            color: var(--primary);
            padding-left: 5px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
            text-decoration: none;
        }

        .social-btn:hover {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-3px);
        }

        @media (max-width: 991px) {
            .hero-title { font-size: 2.5rem; }
            .hero-section { padding-top: 140px; padding-bottom: 60px; text-align: center; }
            .hero-image-wrapper { margin-top: 50px; transform: none !important; }
            .hero-img-main { transform: none !important; }
            .about-img-grid { grid-template-columns: 1fr; grid-template-rows: auto; }
            .hero-floating-card { display: none; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <div class="brand-logo">
                    <!-- Gunakan Logo jika ada, jika tidak icon -->
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Logo">
                    @else
                        <i class="fas fa-tooth"></i>
                    @endif
                </div>
                <span class="text-primary">Klinik Gigi Zenith</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#dokter">Dokter</a></li>
                </ul>
                <div class="d-flex gap-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold d-inline-flex align-items-center justify-content-center">Masuk</a>
                    <a href="{{ route('login') }}" class="btn btn-primary-gradient">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content" data-aos="fade-right">
                    <div class="d-inline-block px-3 py-1 rounded-pill bg-light text-primary fw-bold mb-3 border border-primary-subtle">
                        <i class="fas fa-star me-2"></i>Klinik Gigi Terpercaya no. 1
                    </div>
                    <h1 class="hero-title">Senyum Sempurna <br> <span style="color: var(--primary);">Masa Depan Cerah</span></h1>
                    <p class="hero-subtitle">Nikmati perawatan gigi berkualitas premium dengan teknologi terkini dan tim dokter spesialis yang siap mewujudkan senyum impian Anda.</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('login') }}" class="btn btn-primary-gradient btn-lg shadow-lg">
                            <i class="fas fa-calendar-check me-2"></i>Buat Janji Sekarang
                        </a>
                        <a href="#layanan" class="btn btn-light btn-lg rounded-pill px-4 text-dark border shadow-sm">
                            <i class="fas fa-play-circle me-2 text-primary"></i>Lihat Layanan
                        </a>
                    </div>
                    
                    <div class="mt-5 d-flex align-items-center gap-4 justify-content-center justify-content-lg-start">
                        <div class="d-flex">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" class="rounded-circle border border-2 border-white shadow-sm" width="45" alt="">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle border border-2 border-white shadow-sm" width="45" alt="" style="margin-left: -15px;">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" class="rounded-circle border border-2 border-white shadow-sm" width="45" alt="" style="margin-left: -15px;">
                            <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center border border-2 border-white shadow-sm" style="width: 45px; height: 45px; margin-left: -15px; font-size: 12px; font-weight: bold;">1k+</div>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">1.000+ Pasien Puas</div>
                            <div class="text-warning small">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 hero-image-wrapper" data-aos="fade-left">
                    <!-- Menggunakan gambar placeholder berkualitas tinggi dari Unsplash -->
                    <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Klinik Modern" class="hero-img-main">
                    
                    <!-- Floating Cards -->
                    <div class="hero-floating-card card-1">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Terakreditasi</div>
                            <div class="small text-muted">Standar Internasional</div>
                        </div>
                    </div>
                    <div class="hero-floating-card card-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                            <i class="fas fa-user-md fa-lg"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Dokter Ahli</div>
                            <div class="small text-muted">15+ Spesialis</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Box -->
    <div class="container stats-section">
        <div class="row g-4">
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card text-center">
                    <div class="stat-icon-wrapper mx-auto">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="fw-bold mb-1">24/7</h3>
                    <p class="text-muted mb-0 small">Booking Online</p>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card text-center">
                    <div class="stat-icon-wrapper mx-auto">
                        <i class="fas fa-smile"></i>
                    </div>
                    <h3 class="fw-bold mb-1">10k+</h3>
                    <p class="text-muted mb-0 small">Senyum Indah</p>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card text-center">
                    <div class="stat-icon-wrapper mx-auto">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3 class="fw-bold mb-1">100%</h3>
                    <p class="text-muted mb-0 small">Jaminan Kualitas</p>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-card text-center">
                    <div class="stat-icon-wrapper mx-auto">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <h3 class="fw-bold mb-1">ISO</h3>
                    <p class="text-muted mb-0 small">Sertifikasi Resmi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section id="tentang" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                    <div class="about-img-grid">
                        <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Ruang Periksa" class="about-img about-img-1 shadow">
                        <img src="https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Alat Medis" class="about-img about-img-2 shadow">
                        <img src="https://images.unsplash.com/photo-1609840114035-3c981b782dfe?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Dokter Ramah" class="about-img about-img-3 shadow">
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5" data-aos="fade-left">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">Tentang Kami</span>
                    <h2 class="mb-4 display-6 fw-bold">Kesehatan Gigi Anda Adalah Prioritas Utama Kami</h2>
                    <p class="text-muted mb-4 lead">Kami menggabungkan keahlian medis dengan teknologi mutakhir untuk memberikan pengalaman perawatan gigi yang tak terlupakan.</p>
                    
                    <div class="d-flex flex-column gap-4 mb-5">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0 w-12 h-12 rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center text-info" style="width: 50px; height: 50px;">
                                <i class="fas fa-microscope fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Teknologi Terkini</h5>
                                <p class="text-muted m-0">Menggunakan peralatan diagnostik dan perawatan terbaru untuk hasil presisi.</p>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0 w-12 h-12 rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center text-success" style="width: 50px; height: 50px;">
                                <i class="fas fa-heart fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Pendekatan Personal</h5>
                                <p class="text-muted m-0">Setiap pasien unik, begitu pula rencana perawatannya. Kami mendengar kebutuhan Anda.</p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="#layanan" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-5 bg-light">
        <div class="container py-5">
            <div class="section-header" data-aos="fade-up">
                <span class="badge">Layanan Kami</span>
                <h2 class="display-6 fw-bold">Solusi Lengkap Kesehatan Gigi</h2>
                <p class="text-muted">Dari perawatan rutin hingga prosedur estetika kompleks, kami siap melayani Anda.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-teeth"></i>
                        </div>
                        <h4>Pemeriksaan Rutin</h4>
                        <p class="text-muted mb-4">Pencegahan lebih baik daripada pengobatan. Jaga kesehatan gigi dengan check-up rutin.</p>
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-magic"></i>
                        </div>
                        <h4>Whitening & Estetika</h4>
                        <p class="text-muted mb-4">Kembalikan kepercayaan diri Anda dengan senyum yang lebih cerah dan menawan.</p>
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-tooth"></i>
                        </div>
                        <h4>Ortodonti</h4>
                        <p class="text-muted mb-4">Solusi kawat gigi dan aligner untuk merapikan susunan gigi Anda dengan nyaman.</p>
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-child"></i>
                        </div>
                        <h4>Gigi Anak</h4>
                        <p class="text-muted mb-4">Perawatan khusus untuk buah hati dengan pendekatan yang ramah dan menyenangkan.</p>
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h4>Implan Gigi</h4>
                        <p class="text-muted mb-4">Solusi permanen untuk menggantikan gigi yang hilang dengan rasa dan tampilan natural.</p>
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-procedures"></i>
                        </div>
                        <h4>Bedah Mulut</h4>
                        <p class="text-muted mb-4">Penanganan tindakan bedah dengan prosedur aman dan pemulihan cepat.</p>
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Promotion Section (Optional) -->
    @if(file_exists(public_path('images/promo_whitening.png')))
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center bg-dark text-white rounded-5 overflow-hidden shadow-lg p-0 m-0">
                <div class="col-lg-6 p-5">
                    <span class="badge bg-warning text-dark mb-3">Promo Spesial</span>
                    <h2 class="display-5 fw-bold mb-4 text-white">Ingin Senyum Lebih Cerah?</h2>
                    <p class="lead text-white-50 mb-4">Dapatkan diskon spesial 20% untuk perawatan Whitening di bulan ini. Kuota terbatas!</p>
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg rounded-pill fw-bold text-primary">Klaim Promo</a>
                </div>
                <div class="col-lg-6 h-100 p-0" style="min-height: 400px; background: url('{{ asset('images/promo_whitening.png') }}') center/cover;">
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Call to Action -->
    <section class="cta-section text-center text-white">
        <div class="cta-bg-pattern"></div>
        <div class="container position-relative z-2">
            <h2 class="display-4 fw-bold mb-4 text-primary">Wujudkan Senyum Impian Anda Hari Ini</h2>
            <p class="lead mb-5 text-light opacity-75 mx-auto" style="max-width: 700px;">Jangan tunda kesehatan gigi Anda. Tim profesional kami siap memberikan layanan terbaik dengan hasil yang memuaskan.</p>
            <a href="{{ route('login') }}" class="btn btn-primary-gradient btn-lg px-5 py-3 fs-5 shadow-lg">
                <i class="fas fa-calendar-alt me-2"></i> Reservasi Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row gy-5 mb-5">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <h4 class="mb-0 text-primary fw-bold">Klinik Gigi Sehat</h4>
                    </div>
                    <p class="text-muted mb-4">Memberikan layanan kesehatan gigi terbaik dengan teknologi modern dan dokter berpengalaman untuk senyum sehat keluarga Indonesia.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h5>Navigasi</h5>
                    <a href="#beranda" class="footer-link">Beranda</a>
                    <a href="#tentang" class="footer-link">Tentang Kami</a>
                    <a href="#layanan" class="footer-link">Layanan</a>
                    <a href="#dokter" class="footer-link">Dokter</a>
                </div>
                <div class="col-lg-2 col-6">
                    <h5>Layanan</h5>
                    <a href="#" class="footer-link">Pemeriksaan Gigi</a>
                    <a href="#" class="footer-link">Whitening</a>
                    <a href="#" class="footer-link">Kawat Gigi</a>
                    <a href="#" class="footer-link">Implan</a>
                </div>
                <div class="col-lg-4">
                    <h5>Hubungi Kami</h5>
                    <div class="d-flex gap-3 mb-3">
                        <div class="text-primary"><i class="fas fa-map-marker-alt"></i></div>
                        <p class="text-muted m-0">Jl. Sehat Raya No. 123, Jakarta Selatan, DKI Jakarta 12345</p>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <div class="text-primary"><i class="fas fa-phone"></i></div>
                        <p class="text-muted m-0">+62 21 1234 5678</p>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="text-primary"><i class="fas fa-envelope"></i></div>
                        <p class="text-muted m-0">info@klinikgigisehat.com</p>
                    </div>
                </div>
            </div>
            <div class="border-top py-4 text-center text-muted small">
                &copy; {{ date('Y') }} Klinik Gigi Sehat. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
