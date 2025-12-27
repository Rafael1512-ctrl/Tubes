<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Gigi Sehat - Senyum Sehat, Hidup Berkualitas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --secondary: #10b981;
            --accent: #f59e0b;
            --dark: #1e293b;
            --light: #f8fafc;
            --glass: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--dark);
            overflow-x: hidden;
            background: var(--light);
        }

        /* Navbar */
        .navbar {
            background: var(--glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--glass-border);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 12px 0;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 24px;
            color: var(--primary);
            text-decoration: none;
        }

        .navbar-brand img {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            object-fit: cover;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 12px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(37, 99, 235, 0.4);
            color: white;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.95) 0%, rgba(29, 78, 216, 0.9) 100%),
                        url('{{ asset("images/hero.png") }}') center/cover no-repeat;
            min-height: 90vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
            animation: fadeInUp 0.8s ease;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            opacity: 0.95;
            animation: fadeInUp 0.8s ease 0.2s backwards;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease 0.4s backwards;
        }

        .btn-hero {
            padding: 16px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-hero-primary {
            background: white;
            color: var(--primary);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
            color: var(--primary-dark);
        }

        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-hero-outline:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-3px);
        }

        /* Stats Section */
        .stats-section {
            background: white;
            padding: 60px 0;
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            color: white;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 42px;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .stat-number {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 16px;
            opacity: 0.9;
        }

        /* Section Styles */
        section {
            padding: 80px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: 42px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .section-header p {
            font-size: 18px;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Services */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .service-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
            border-color: var(--primary);
        }

        .service-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 32px;
            color: white;
        }

        .service-card h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--dark);
        }

        .service-card p {
            color: #64748b;
            line-height: 1.7;
        }

        /* Why Choose Us */
        .why-choose {
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .feature-card {
            background: white;
            padding: 35px 25px;
            border-radius: 16px;
            display: flex;
            gap: 20px;
            align-items: start;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateX(10px);
        }

        .feature-icon {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, var(--secondary) 0%, #059669 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: white;
            font-size: 24px;
        }

        .feature-content h4 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--dark);
        }

        .feature-content p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .cta-section h2 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .cta-section p {
            font-size: 18px;
            margin-bottom: 35px;
            opacity: 0.95;
            position: relative;
            z-index: 2;
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 60px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h4 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-section p, .footer-section a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            line-height: 1.8;
            display: block;
            margin-bottom: 10px;
        }

        .footer-section a:hover {
            color: var(--primary-light);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 36px;
            }
            
            .hero p {
                font-size: 16px;
            }

            .section-header h2 {
                font-size: 32px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn-hero {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a href="/" class="navbar-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Klinik">
                    <span>Klinik Gigi Sehat</span>
                </a>
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 hero-content">
                    <h1>Senyum Sehat, <br>Hidup Berkualitas</h1>
                    <p>Klinik gigi modern dengan teknologi terkini dan tim dokter berpengalaman. Kami berkomitmen memberikan perawatan terbaik untuk kesehatan gigi dan senyum indah Anda.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('login') }}" class="btn-hero btn-hero-primary">
                            <i class="fas fa-calendar-check me-2"></i>Buat Janji Sekarang
                        </a>
                        <a href="#layanan" class="btn-hero btn-hero-outline">
                            <i class="fas fa-info-circle me-2"></i>Lihat Layanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <div class="stat-number">10K+</div>
                <div class="stat-label">Pasien Puas</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-md"></i>
                <div class="stat-number">15+</div>
                <div class="stat-label">Dokter Ahli</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-award"></i>
                <div class="stat-number">20+</div>
                <div class="stat-label">Tahun Pengalaman</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-star"></i>
                <div class="stat-number">ISO</div>
                <div class="stat-label">Certified Clinic</div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section id="tentang">
        <div class="container">
            <div class="section-header">
                <h2>Tentang Kami</h2>
                <p>Klinik Gigi Sehat telah melayani masyarakat selama lebih dari 20 tahun dengan dedikasi penuh pada kesehatan gigi Anda</p>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('images/hero.png') }}" alt="Klinik Gigi Sehat" class="img-fluid rounded-4 shadow-lg">
                </div>
                <div class="col-lg-6">
                    <h3 class="mb-4 fw-bold">Komitmen Kami untuk Senyum Sehat Anda</h3>
                    <p class="text-muted mb-3">
                        Klinik Gigi Sehat didirikan dengan visi menjadi pusat perawatan gigi terpercaya yang mengutamakan kenyamanan dan kepuasan pasien. Kami menggunakan teknologi dental terkini dan dikombinasikan dengan pendekatan personal untuk setiap pasien.
                    </p>
                    <p class="text-muted mb-4">
                        Tim dokter gigi kami terdiri dari spesialis berpengalaman yang terus mengikuti perkembangan ilmu kedokteran gigi terbaru. Fasilitas klinik kami dirancang untuk memberikan pengalaman perawatan yang nyaman dan higienis.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-lg" style="background: var(--primary); color: white; border-radius: 12px; padding: 14px 35px;">
                        <i class="fas fa-phone-alt me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" style="background: var(--light);">
        <div class="container">
            <div class="section-header">
                <h2>Layanan Kami</h2>
                <p>Berbagai layanan perawatan gigi profesional untuk kebutuhan Anda</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-tooth"></i>
                    </div>
                    <h3>Pemeriksaan Umum</h3>
                    <p>Pemeriksaan gigi rutin dan pembersihan karang gigi untuk menjaga kesehatan mulut Anda.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-teeth-open"></i>
                    </div>
                    <h3>Ortodonti (Behel)</h3>
                    <p>Pemasangan kawat gigi untuk merapikan susunan gigi dengan hasil optimal.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-smile-beam"></i>
                    </div>
                    <h3>Pemutihan Gigi</h3>
                    <p>Teknologi whitening terkini untuk membuat senyum Anda lebih cerah dan percaya diri.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-procedures"></i>
                    </div>
                    <h3>Implant Gigi</h3>
                    <p>Solusi permanen untuk mengganti gigi yang hilang dengan hasil natural.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-x-ray"></i>
                    </div>
                    <h3>Rontgen Digital</h3>
                    <p>Diagnosis akurat dengan teknologi rontgen digital yang aman dan cepat.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3>Perawatan Anak</h3>
                    <p>Layanan khusus untuk perawatan gigi anak dengan pendekatan yang ramah.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-choose">
        <div class="container">
            <div class="section-header">
                <h2>Mengapa Memilih Kami?</h2>
                <p>Keunggulan yang membuat kami menjadi pilihan utama untuk kesehatan gigi Anda</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Dokter Berpengalaman</h4>
                        <p>Tim dokter spesialis dengan pengalaman lebih dari 15 tahun di bidangnya.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-laptop-medical"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Teknologi Modern</h4>
                        <p>Peralatan dental terkini untuk hasil perawatan yang optimal.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Sertifikasi ISO</h4>
                        <p>Klinik bersertifikat ISO untuk standar kualitas internasional.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-hand-holding-medical"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Layanan Ramah</h4>
                        <p>Staf yang profesional dan ramah siap melayani Anda dengan baik.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Sterilisasi Ketat</h4>
                        <p>Protokol sterilisasi yang ketat untuk keamanan maksimal.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Jadwal Fleksibel</h4>
                        <p>Tersedia berbagai pilihan waktu konsultasi sesuai kebutuhan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Siap untuk Senyum yang Lebih Sehat?</h2>
            <p>Jadwalkan konsultasi Anda hari ini dan mulai perjalanan menuju senyum yang sempurna</p>
            <a href="{{ route('login') }}" class="btn-hero btn-hero-primary">
                <i class="fas fa-calendar-plus me-2"></i>Buat Janji Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Klinik Gigi Sehat</h4>
                    <p>Klinik gigi modern yang mengutamakan kenyamanan dan kepuasan pasien dengan teknologi terkini.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Layanan</h4>
                    <a href="#layanan">Pemeriksaan Umum</a>
                    <a href="#layanan">Ortodonti (Behel)</a>
                    <a href="#layanan">Pemutihan Gigi</a>
                    <a href="#layanan">Implant Gigi</a>
                </div>
                <div class="footer-section">
                    <h4>Kontak</h4>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Jl. Sehat Raya No. 123, Jakarta</p>
                    <p><i class="fas fa-phone me-2"></i> (021) 1234-5678</p>
                    <p><i class="fas fa-envelope me-2"></i> info@klinikgigisehat.com</p>
                </div>
                <div class="footer-section">
                    <h4>Jam Operasional</h4>
                    <p>Senin - Jumat: 08:00 - 20:00</p>
                    <p>Sabtu: 08:00 - 17:00</p>
                    <p>Minggu: 09:00 - 14:00</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Klinik Gigi Sehat. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Smooth Scroll -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
