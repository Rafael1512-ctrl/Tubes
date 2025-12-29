<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Zenith Dental')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Default Theme (Pasien - Blue/Purple) */
            --primary: #2563eb;
            --primary-soft: #dbeafe;
            --secondary: #64748b;
            --bg-body: #f1f5f9;
            --glass: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.5);
            --sidebar-width: 280px;
        }

        /* Admin Theme (Professional Dark/Slate) */
        [data-theme="admin"] {
            --primary: #0f172a;
            --primary-soft: #e2e8f0;
            --bg-body: #f8fafc;
        }

        /* Dokter Theme (Medical Teal/Green) */
        [data-theme="dokter"] {
            --primary: #0d9488;
            --primary-soft: #ccfbf1;
            --bg-body: #f0fdfa;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            background-image: radial-gradient(at 0% 0%, var(--primary-soft) 0, transparent 50%), 
                            radial-gradient(at 100% 0%, var(--primary-soft) 0, transparent 50%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--glass);
            backdrop-filter: blur(12px);
            border-right: 1px solid var(--glass-border);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 3rem;
            color: var(--primary);
            font-weight: 800;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--secondary);
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }

        /* Topbar */
        .topbar {
            background: var(--glass);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Cards */
        .card-custom {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
    @stack('styles')
</head>
<body data-theme="@yield('theme', 'pasien')">

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <a href="#" class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" width="40" height="40" class="rounded-circle">
            Zenith Dental
        </a>
        
        <div class="nav flex-column">
            @yield('sidebar-menu')
        </div>

        <div class="mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="nav-link w-100 text-start text-danger border-0 bg-transparent">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header Mobile -->
        <div class="d-lg-none mb-3">
            <button class="btn btn-primary" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h5 class="m-0 fw-bold">@yield('header-title', 'Dashboard')</h5>
                <small class="text-muted">@yield('header-subtitle', 'Welcome back!')</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <button class="bg-white p-2 rounded-circle shadow-sm border-0 position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-bell text-primary"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" style="width: 320px; border-radius: 15px; overflow: hidden;">
                        <div class="p-3 bg-primary text-white">
                            <h6 class="m-0">Notifikasi</h6>
                        </div>
                        <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                            @forelse(Auth::user()->notifications()->limit(5)->get() as $notification)
                                <a href="{{ $notification->data['link'] ?? '#' }}" class="list-group-item list-group-item-action p-3 @if(!$notification->read_at) bg-light @endif">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-1 fw-bold small">{{ $notification->data['title'] ?? 'Notifikasi' }}</h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 small text-muted">{{ Str::limit($notification->data['message'] ?? '', 80) }}</p>
                                </a>
                            @empty
                                <div class="p-4 text-center">
                                    <i class="fa-solid fa-bell-slash text-muted mb-2"></i>
                                    <p class="mb-0 small text-muted">Tidak ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>
                        <a href="{{ route(Auth::user()->role . '.notifications' ?? 'home') }}" class="p-2 text-center d-block small text-primary text-decoration-none border-top">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=random" class="rounded-circle" width="40">
                    <div class="d-none d-md-block">
                        <small class="d-block fw-bold">{{ Auth::user()->name ?? 'Guest' }}</small>
                        <small class="text-muted">{{ ucfirst(Auth::user()->role ?? 'Visitor') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @yield('content')

    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
