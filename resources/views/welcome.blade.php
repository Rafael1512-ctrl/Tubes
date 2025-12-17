<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Gigi Modern - Senyum Sehat Anda</title>
    <link href="{{ asset('css/modern.css') }}" rel="stylesheet">
    <!-- Font Awesome for Icons (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-main: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-content">
            <a href="#" class="logo">
                <i class="fas fa-tooth"></i>
                Dental<span>Care</span>
            </a>
            <div class="nav-links">
                <a href="#home">Beranda</a>
                <a href="#services">Layanan</a>
                <a href="#doctors">Dokter</a>
                <a href="#contact">Kontak</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container hero-content">
            <div class="hero-text">
                <h1>Senyum Indah Dimulai Di Sini</h1>
                <p>Kami menyediakan perawatan gigi terbaik dengan teknologi modern dan tim dokter spesialis yang berpengalaman. Kenyamanan dan kesehatan gigi Anda adalah prioritas kami.</p>
                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('login') }}" class="btn btn-primary">Buat Janji Sekarang</a>
                    <a href="#services" class="btn btn-outline">Lihat Layanan</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/hero.png') }}" alt="Klinik Gigi Modern">
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container stats-grid">
            <div class="stat-item">
                <h3>15+</h3>
                <p>Tahun Pengalaman</p>
            </div>
            <div class="stat-item">
                <h3>50+</h3>
                <p>Dokter Spesialis</p>
            </div>
            <div class="stat-item">
                <h3>10k+</h3>
                <p>Pasien Puas</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Layanan Darurat</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section">
        <div class="container">
            <div class="section-title">
                <h2>Layanan Unggulan</h2>
                <p>Solusi kesehatan gigi komprehensif untuk seluruh keluarga</p>
            </div>
            <div class="grid-3">
                <div class="card">
                    <div class="icon-box">
                        <i class="fas fa-teeth"></i>
                    </div>
                    <h3>Pemeriksaan Rutin</h3>
                    <p>Pemeriksaan kesehatan gigi menyeluruh untuk mencegah masalah gigi sejak dini.</p>
                </div>
                <div class="card">
                    <div class="icon-box">
                        <i class="fas fa-teeth-open"></i>
                    </div>
                    <h3>Tambal Gigi</h3>
                    <p>Perawatan penambalan gigi berlubang dengan bahan berkualitas tinggi dan tahan lama.</p>
                </div>
                <div class="card">
                    <div class="icon-box">
                        <i class="fas fa-magic"></i>
                    </div>
                    <h3>Pemutihan Gigi</h3>
                    <p>Dapatkan senyum lebih cerah dengan layanan pemutihan gigi profesional kami.</p>
                </div>
                <div class="card">
                    <div class="icon-box">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3>Gigi Anak</h3>
                    <p>Perawatan khusus untuk kesehatan gigi anak dengan pendekatan yang ramah dan nyaman.</p>
                </div>
                <div class="card">
                    <div class="icon-box">
                        <i class="fas fa-hospital-user"></i>
                    </div>
                    <h3>Bedah Mulut</h3>
                    <p>Tindakan bedah minor seperti pencabutan gigi bungsu oleh spesialis bedah mulut.</p>
                </div>
                <div class="card">
                    <div class="icon-box">
                        <i class="fas fa-x-ray"></i>
                    </div>
                    <h3>Rontgen Gigi</h3>
                    <p>Fasilitas rontgen digital untuk diagnosis yang akurat dan cepat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-info">
                    <div class="logo" style="color: white; margin-bottom: 1rem;">
                        <i class="fas fa-tooth"></i>
                        Dental<span style="color: var(--accent);">Care</span>
                    </div>
                    <p>Klinik gigi modern dengan standar pelayanan internasional. Kami hadir untuk memberikan senyum terbaik bagi Anda dan keluarga.</p>
                </div>
                <div class="footer-col">
                    <h4>Tautan Cepat</h4>
                    <ul>
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#services">Layanan</a></li>
                        <li><a href="#doctors">Dokter</a></li>
                        <li><a href="{{ route('login') }}">Masuk</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Layanan</h4>
                    <ul>
                        <li>Pemeriksaan Gigi</li>
                        <li>Pencabutan Gigi</li>
                        <li>Scaling</li>
                        <li>Bleaching</li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Hubungi Kami</h4>
                    <ul>
                        <li><i class="fas fa-phone"></i> (021) 1234-5678</li>
                        <li><i class="fas fa-envelope"></i> info@dentalcare.id</li>
                        <li><i class="fas fa-map-marker-alt"></i> Jl. Sehat Selalu No. 123, Jakarta</li>
                    </ul>
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 2rem; text-align: center;">
                <p>&copy; {{ date('Y') }} DentalCare Klinik. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
