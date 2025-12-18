<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'BrightSmile Care') }} - @yield('title')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 Check -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top py-3 transition-all" id="mainNav">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2" href="{{ url('/') }}">
                <i class="fas fa-tooth fa-lg"></i>
                <span>BrightSmile<span class="text-primary-light">Care</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-lg-3 align-items-center">
                    <li class="nav-item"><a class="nav-link fw-medium" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#services">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#team">Dokter</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#blog">Artikel</a></li>
                    <li class="nav-item">
                        <a href="{{ route('landing.booking') }}" class="btn btn-light text-primary rounded-pill px-4 fw-bold shadow-sm">Booking Online</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3 mt-auto">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="mb-3 d-flex align-items-center gap-2 fs-4 fw-bold">
                        <i class="fas fa-tooth text-primary"></i> BrightSmile Care
                    </div>
                    <p class="text-white-50">Klinik gigi estetika profesional dengan standar pelayanan internasional. Wujudkan senyum impian Anda bersama kami.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-white hover-opacity"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white hover-opacity"><i class="fab fa-whatsapp fa-lg"></i></a>
                        <a href="#" class="text-white hover-opacity"><i class="fab fa-facebook fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1">
                    <h5 class="fw-bold mb-4">Quick Links</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#" class="text-white-50 text-decoration-none hover-white">Tentang Kami</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none hover-white">Layanan Estetika</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none hover-white">Tim Dokter</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none hover-white">Booking Online</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-4">Kontak Kami</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3 text-white-50">
                        <li class="d-flex gap-3">
                            <i class="fas fa-map-marker-alt mt-1 text-primary"></i>
                            <span>Jl. Babakan Jeruk Indah I No.7, Sukagalih, Kec. Sukajadi, Kota Bandung, Jawa Barat 40163</span>
                        </li>
                        <li class="d-flex gap-3">
                            <i class="fas fa-phone-alt mt-1 text-primary"></i>
                            <span>0895-3608-2817</span>
                        </li>
                        <li class="d-flex gap-3">
                            <i class="fas fa-envelope mt-1 text-primary"></i>
                            <span>info@brightsmilecare.clinic</span>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary opacity-25">
            <div class="text-center text-white-50 small mt-3">
                &copy; {{ date('Y') }} BrightSmile Care. All Rights Reserved.
            </div>
        </div>
    </footer>

     <!-- Floating WA Button -->
    <a href="https://wa.me/6289536082817" class="floating-wa shadow-lg" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-primary-dark', 'shadow-sm', 'scrolled');
                navbar.classList.remove('bg-transparent', 'py-3');
                navbar.classList.add('py-2');
            } else {
                navbar.classList.remove('bg-primary-dark', 'shadow-sm', 'scrolled');
                navbar.classList.add('bg-transparent', 'py-3');
                navbar.classList.remove('py-2');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
