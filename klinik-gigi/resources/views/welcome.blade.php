<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Gigi Zenith - Senyum Sempurna Dimulai Di Sini</title>
    
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
            color: #64748b;
            margin: 0 15px;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
        }

        .navbar.scrolled .nav-link {
            color: #475569;
        }

        .navbar.scrolled .nav-link:hover, .navbar.scrolled .nav-link.active {
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
            background: #0f172a;
            color: #94a3b8;
            padding-top: 100px;
            border-top: 1px solid rgba(255,255,255,0.05);
            position: relative;
        }

        .footer h5 {
            color: white;
            margin-bottom: 25px;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
        }

        .footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--gradient-primary);
        }

        .footer-link {
            display: block;
            color: #94a3b8;
            margin-bottom: 15px;
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-link i {
            font-size: 0.8rem;
            opacity: 0;
            transition: all 0.3s;
        }

        .footer-link:hover {
            color: white;
            padding-left: 10px;
        }

        .footer-link:hover i {
            opacity: 1;
        }

        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .social-btn:hover {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-5px);
            border-color: transparent;
            box-shadow: var(--shadow-glow);
        }

        .footer-bottom {
            background: #020617;
            padding: 30px 0;
            margin-top: 80px;
        }

        /* Modal Auth Tabs Legibility */
        #authTab .nav-link {
            color: #64748b;
            border: none;
            background: transparent;
        }

        #authTab .nav-link.active {
            background: var(--gradient-primary);
            color: white !important;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        #authTab .nav-link:not(.active):hover {
            color: var(--primary);
        }

        /* Doctor Section Card */
        .doctor-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .doctor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border-color: var(--primary-soft);
        }

        .doctor-img-wrapper {
            position: relative;
            padding: 20px 20px 0 20px;
            overflow: hidden;
        }

        .doctor-img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 20px;
            transition: transform 0.5s ease;
        }

        .doctor-card:hover .doctor-img {
            transform: scale(1.05);
        }

        .doctor-info {
            padding: 25px;
            text-align: center;
        }

        .doctor-name {
            font-size: 1.25rem;
            margin-bottom: 5px;
            color: var(--dark);
        }

        .doctor-specialty {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: block;
        }

        .doctor-social {
            display: flex;
            justify-content: center;
            gap: 15px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .doctor-card:hover .doctor-social {
            opacity: 1;
            transform: translateY(0);
        }

        .doctor-social-link {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary);
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .doctor-social-link:hover {
            background: var(--primary);
            color: white;
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
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#dokter">Dokter</a></li>
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                </ul>
                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4 fw-bold d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#loginModal" onclick="showTab('login-tab')">Masuk</button>
                    <button type="button" class="btn btn-primary-gradient" data-bs-toggle="modal" data-bs-target="#loginModal" onclick="showTab('register-tab')">Daftar</button>
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
                        <button type="button" class="btn btn-primary-gradient btn-lg shadow-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fas fa-calendar-check me-2"></i>Buat Janji Sekarang
                        </button>
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

    <!-- Doctor Section -->
    <section id="dokter" class="py-5">
        <div class="container py-5">
            <div class="section-header" data-aos="fade-up">
                <span class="badge">Tenaga Ahli Kami</span>
                <h2 class="display-6 fw-bold">Bertemu Dengan Tim Dokter Spesialis</h2>
                <p class="text-muted">Kami memiliki tim dokter yang sangat berpengalaman dan berdedikasi tinggi untuk memberikan perawatan gigi terbaik bagi Anda.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="doctor-card">
                        <div class="doctor-img-wrapper">
                            <img src="{{ asset('images/Rafael.png') }}" alt="Rafael" class="doctor-img">
                        </div>
                        <div class="doctor-info">
                            <h5 class="doctor-name fw-bold">Rafael</h5>
                            <span class="doctor-specialty">Dokter Gigi</span>
                            <div class="doctor-social">
                                <a href="#" class="doctor-social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="doctor-social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="doctor-social-link"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="doctor-card">
                        <div class="doctor-img-wrapper">
                            <img src="{{ asset('images/Budi_Santoso.png') }}" alt="Budi Santoso" class="doctor-img">
                        </div>
                        <div class="doctor-info">
                            <h5 class="doctor-name fw-bold">Budi Santoso</h5>
                            <span class="doctor-specialty">Dokter Spesialis</span>
                            <div class="doctor-social">
                                <a href="#" class="doctor-social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="doctor-social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="doctor-social-link"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="doctor-card">
                        <div class="doctor-img-wrapper">
                            <img src="{{ asset('images/Sari_Mawar.png') }}" alt="Sari Mawar" class="doctor-img">
                        </div>
                        <div class="doctor-info">
                            <h5 class="doctor-name fw-bold">Sari Mawar</h5>
                            <span class="doctor-specialty">Dokter Spesialis</span>
                            <div class="doctor-social">
                                <a href="#" class="doctor-social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="doctor-social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="doctor-social-link"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="doctor-card">
                        <div class="doctor-img-wrapper">
                            <img src="{{ asset('images/Andi_Wijaya.png') }}" alt="Andi Wijaya" class="doctor-img">
                        </div>
                        <div class="doctor-info">
                            <h5 class="doctor-name fw-bold">Andi Wijaya</h5>
                            <span class="doctor-specialty">Dokter Spesialis</span>
                            <div class="doctor-social">
                                <a href="#" class="doctor-social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="doctor-social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="doctor-social-link"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
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
                        <button type="button" class="btn btn-link text-primary fw-bold text-decoration-none p-0 border-0" data-bs-toggle="modal" data-bs-target="#loginModal">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-magic"></i>
                        </div>
                        <h4>Whitening & Estetika</h4>
                        <p class="text-muted mb-4">Kembalikan kepercayaan diri Anda dengan senyum yang lebih cerah dan menawan.</p>
                        <button type="button" class="btn btn-link text-primary fw-bold text-decoration-none p-0 border-0" data-bs-toggle="modal" data-bs-target="#loginModal">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-tooth"></i>
                        </div>
                        <h4>Ortodonti</h4>
                        <p class="text-muted mb-4">Solusi kawat gigi dan aligner untuk merapikan susunan gigi Anda dengan nyaman.</p>
                        <button type="button" class="btn btn-link text-primary fw-bold text-decoration-none p-0 border-0" data-bs-toggle="modal" data-bs-target="#loginModal">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-child"></i>
                        </div>
                        <h4>Gigi Anak</h4>
                        <p class="text-muted mb-4">Perawatan khusus untuk buah hati dengan pendekatan yang ramah dan menyenangkan.</p>
                        <button type="button" class="btn btn-link text-primary fw-bold text-decoration-none p-0 border-0" data-bs-toggle="modal" data-bs-target="#loginModal">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h4>Implan Gigi</h4>
                        <p class="text-muted mb-4">Solusi permanen untuk menggantikan gigi yang hilang dengan rasa dan tampilan natural.</p>
                        <button type="button" class="btn btn-link text-primary fw-bold text-decoration-none p-0 border-0" data-bs-toggle="modal" data-bs-target="#loginModal">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-procedures"></i>
                        </div>
                        <h4>Bedah Mulut</h4>
                        <p class="text-muted mb-4">Penanganan tindakan bedah dengan prosedur aman dan pemulihan cepat.</p>
                        <button type="button" class="btn btn-link text-primary fw-bold text-decoration-none p-0 border-0" data-bs-toggle="modal" data-bs-target="#loginModal">Booking Jadwal <i class="fas fa-arrow-right ms-1"></i></button>
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
            <button type="button" class="btn btn-primary-gradient btn-lg px-5 py-3 fs-5 shadow-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fas fa-calendar-alt me-2"></i> Reservasi Sekarang
            </button>
        </div>
    </section>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
                <div class="modal-header border-0 pb-0 pt-4 px-4 justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <!-- Session Feedback -->
                    @if (session('success'))
                        <div class="alert alert-success border-0 rounded-4 small mb-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger border-0 rounded-4 small mb-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-4 small mb-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Tabs for Login/Register -->
                    <ul class="nav nav-pills nav-fill mb-4 bg-light rounded-pill p-1 shadow-sm" id="authTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ !old('name') ? 'active' : '' }} rounded-pill fw-bold" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="{{ !old('name') ? 'true' : 'false' }}" >Masuk</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ old('name') ? 'active' : '' }} rounded-pill fw-bold" id="register-tab" data-bs-toggle="pill" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="{{ old('name') ? 'true' : 'false' }}">Daftar</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="authTabContent">
                        <!-- Login Form -->
                        <div class="tab-pane fade {{ !old('name') ? 'show active' : '' }}" id="login" role="tabpanel" aria-labelledby="login-tab">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold">Selamat Datang</h4>
                                <p class="text-muted small">Silakan masuk ke akun Anda</p>
                            </div>

                            <form method="POST" action="{{ route('login.post') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 1px;">Email Address</label>
                                    <div class="input-group input-group-lg shadow-sm">
                                        <span class="input-group-text bg-white border-end-0 rounded-start-4"><i class="fas fa-envelope text-primary opacity-75"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 rounded-end-4 bg-white fs-6" placeholder="nama@email.com" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 1px;">Password</label>
                                    <div class="input-group input-group-lg shadow-sm">
                                        <span class="input-group-text bg-white border-end-0 rounded-start-4"><i class="fas fa-lock text-primary opacity-75"></i></span>
                                        <input type="password" name="password" class="form-control border-start-0 rounded-end-4 bg-white fs-6" placeholder="••••••••" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary-gradient w-100 py-3 rounded-pill fw-bold shadow-glow mb-4">
                                    Masuk ke Dashboard
                                </button>
                                
                                <div class="text-center">
                                    <p class="small text-muted mb-0">Lupa password? <a href="#" class="text-primary fw-bold text-decoration-none">Hubungi Admin</a></p>
                                </div>
                            </form>
                        </div>

                        <!-- Register Form -->
                        <div class="tab-pane fade {{ old('name') ? 'show active' : '' }}" id="register" role="tabpanel" aria-labelledby="register-tab">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold">Buat Akun Baru</h4>
                                <p class="text-muted small">Daftar sebagai pasien baru klinik</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 1px;">Nama Lengkap</label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-end-0 rounded-start-3"><i class="fas fa-user text-primary opacity-75"></i></span>
                                        <input type="text" name="name" class="form-control border-start-0 rounded-end-3 bg-white" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-7">
                                        <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 1px;">Email Address</label>
                                        <input type="email" name="email" class="form-control rounded-3 bg-white shadow-sm" placeholder="nama@email.com" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 1px;">Gender</label>
                                        <select name="jenis_kelamin" class="form-select rounded-3 bg-white shadow-sm" required>
                                            <option value="">Pilih</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 1px;">Nomor Telepon</label>
                                        <input type="text" name="no_telp" class="form-control rounded-3 bg-white shadow-sm" placeholder="08xxxxxxxxxx" value="{{ old('no_telp') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 1px;">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control rounded-3 bg-white shadow-sm" value="{{ old('tanggal_lahir') }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 1px;">Alamat Tinggal</label>
                                    <textarea name="alamat" class="form-control rounded-3 bg-white shadow-sm" rows="2" placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 1px;">Password</label>
                                        <input type="password" name="password" class="form-control rounded-3 bg-white shadow-sm" placeholder="••••••••" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 1px;">Konfirmasi</label>
                                        <input type="password" name="password_confirmation" class="form-control rounded-3 bg-white shadow-sm" placeholder="••••••••" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary-gradient w-100 py-3 rounded-pill fw-bold shadow-glow mb-3">
                                    Daftar Sekarang
                                </button>
                                <p class="text-center text-muted small mt-3">
                                    Sudah memiliki akun? <a href="javascript:void(0)" onclick="showTab('login-tab')" class="text-primary fw-bold text-decoration-none">Masuk di sini</a>
                                </p>
                                <p class="text-center text-muted x-small">
                                    Dengan mendaftar, Anda menyetujui <a href="#" class="text-decoration-none text-primary">syarat & ketentuan</a> kami.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-5 pe-lg-5">
                    <a class="navbar-brand mb-4 d-inline-block" href="/">
                        <div class="brand-logo shadow-glow">
                             <i class="fas fa-tooth"></i>
                        </div>
                        <span class="text-white">Zenith Dental</span>
                    </a>
                    <p class="mb-4 text-opacity-75" style="max-width: 400px; line-height: 1.8;">Membangun masa depan senyum Indonesia dengan teknologi kedokteran gigi tercanggih dan pelayanan setulus hati untuk setiap keluarga.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <h5>Layanan Cepat</h5>
                    <div class="footer-links">
                        <a href="#beranda" class="footer-link"><i class="fas fa-chevron-right"></i> Beranda</a>
                        <a href="#tentang" class="footer-link"><i class="fas fa-chevron-right"></i> Tentang Kami</a>
                        <a href="#layanan" class="footer-link"><i class="fas fa-chevron-right"></i> Layanan Medis</a>
                        <a href="#dokter" class="footer-link"><i class="fas fa-chevron-right"></i> Tim Dokter</a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#loginModal" class="footer-link"><i class="fas fa-chevron-right"></i> Buat Janji Temu</a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <h5>Informasi Kontak</h5>
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex gap-3">
                            <div class="social-btn" style="width: 40px; height: 40px; background: rgba(14, 165, 233, 0.1); border: none; color: var(--primary);">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <div class="text-white fw-bold mb-1 small">Lokasi Klinik</div>
                                <p class="m-0 small opacity-75">Jl. Sehat Raya No. 123, Jakarta Selatan</p>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="social-btn" style="width: 40px; height: 40px; background: rgba(45, 212, 191, 0.1); border: none; color: var(--secondary);">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <div class="text-white fw-bold mb-1 small">Telepon & WA</div>
                                <p class="m-0 small opacity-75">+62 21 1234 5678</p>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="social-btn" style="width: 40px; height: 40px; background: rgba(245, 158, 11, 0.1); border: none; color: var(--accent);">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <div class="text-white fw-bold mb-1 small">Email Support</div>
                                <p class="m-0 small opacity-75">hello@zenithdental.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <p class="m-0 small opacity-50">&copy; {{ date('Y') }} Klinik Gigi Zenith. Crafted with <i class="fas fa-heart text-danger mx-1"></i> for your smile.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="d-flex justify-content-center justify-content-md-end gap-4 small opacity-50">
                            <a href="#" class="text-white text-decoration-none">Privacy Policy</a>
                            <a href="#" class="text-white text-decoration-none">Terms of Service</a>
                        </div>
                    </div>
                </div>
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

        // Function to show specific tab in login modal
        function showTab(tabId) {
            const triggerEl = document.querySelector('#' + tabId);
            if (triggerEl) {
                bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            }
        }

        // Auto open login modal if there are errors (Back from failed attempt)
        @if($errors->any() || session('error') || session('success'))
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        @endif

        // Intersection Observer for Active Nav Links
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${id}`) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
</html>
