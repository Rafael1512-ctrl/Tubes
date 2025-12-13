<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Gigi - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }
        .sidebar a {
            color: #ecf0f1;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background: #34495e;
            text-decoration: none;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @auth
            <div class="col-md-2 p-0 sidebar">
                <h4 class="text-white p-3">Klinik Gigi</h4>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('pasien.index') }}">Pasien</a>
                <a href="{{ route('jadwal.index') }}">Jadwal</a>
                <a href="{{ route('booking.index') }}">Booking</a>
                @if(auth()->user()->isAdmin())
                    <a href="#">Dokter</a>
                    <a href="#">Laporan</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('pegawai.index') }}">Dokter</a>
                    <a href="{{ route('admin.jadwal') }}">Kelola Jadwal</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="mt-3 p-3">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm w-100">Logout</button>
                </form>
            </div>
            @endauth
            
            <!-- Content -->
            <div class="@auth col-md-10 @else col-12 @endauth content">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>