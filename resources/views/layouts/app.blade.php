<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Gigi - @yield('title', 'Dashboard')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @auth
            <div class="col-md-3 col-lg-2 p-0 sidebar-wrapper">
                <div class="sidebar-brand">
                    <i class="fas fa-tooth"></i> DentalCare
                </div>
                <div class="sidebar-menu">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    
                    <a href="{{ route('pasien.index') }}" class="sidebar-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                        <i class="fas fa-user-injured"></i> Pasien
                    </a>
                    
                    <a href="{{ route('jadwal.index') }}" class="sidebar-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Jadwal
                    </a>
                    
                    <a href="{{ route('booking.index') }}" class="sidebar-link {{ request()->routeIs('booking.*') ? 'active' : '' }}">
                        <i class="fas fa-book-medical"></i> Booking
                    </a>

                    @if(auth()->user()->isAdmin())
                        <div class="text-white small fw-bold px-4 mt-3 mb-2 text-uppercase opacity-50">Admin</div>
                        
                        <a href="{{ route('pegawai.index') }}" class="sidebar-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                            <i class="fas fa-user-md"></i> Dokter & Staff
                        </a>
                        
                        <!-- Example Logic for Admin specific links -->
                        <!-- 
                        <a href="{{ route('admin.jadwal') }}" class="sidebar-link">
                            <i class="fas fa-clock"></i> Kelola Jadwal
                        </a>
                         -->
                    @endif
                    
                    <form action="{{ route('logout') }}" method="POST" class="mt-4 px-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-light w-100 btn-sm">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
            @endauth
            
            <!-- Main Content -->
            <div class="{{ auth()->check() ? 'col-md-9 col-lg-10' : 'col-12' }} p-0">
                @auth
                <!-- Top Navbar for Authenticated Users -->
                <div class="top-navbar">
                    <div>
                        <h5 class="m-0 text-muted">Selamat Datang, {{ auth()->user()->username ?? 'User' }}</h5>
                    </div>
                    <div class="user-profile">
                        <div class="user-avatar">
                            {{ substr(auth()->user()->username ?? 'U', 0, 1) }}
                        </div>
                    </div>
                </div>
                @endauth

                <div class="content p-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>