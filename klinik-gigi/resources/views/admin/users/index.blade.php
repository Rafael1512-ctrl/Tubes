@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Manajemen User')
@section('header-title','Manajemen User')
@section('header-subtitle','Kelola akun dokter & pasien')

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.pasien') }}" class="nav-link"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
    <a href="{{ route('admin.obat') }}" class="nav-link"><i class="fa-solid fa-pills"></i> Data Obat</a>
    <a href="{{ route('admin.users') }}" class="nav-link active"><i class="fa-solid fa-users"></i> Manajemen User</a>
    <a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
    <a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('styles')
<style>
    /* Animasi untuk alert */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-down {
        animation: slideDown 0.4s ease-out;
    }

    /* Styling tambahan untuk alert */
    .alert {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-left: 5px solid;
    }

    .alert-success {
        border-left-color: #28a745;
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        border-left-color: #dc3545;
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-warning {
        border-left-color: #ffc107;
        background-color: #fff3cd;
        color: #856404;
    }

    .alert i {
        opacity: 0.8;
    }
</style>
@endsection

@section('content')

{{-- Alert untuk pesan sukses --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-slide-down" role="alert" id="success-alert">
    <div class="d-flex align-items-center">
        <i class="fa-solid fa-circle-check me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Berhasil!</strong>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Alert untuk pesan error --}}
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show animate-slide-down" role="alert" id="error-alert">
    <div class="d-flex align-items-center">
        <i class="fa-solid fa-circle-exclamation me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Gagal!</strong>
            <p class="mb-0">{{ session('error') }}</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card-custom mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">Daftar User</h4>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Tambah User
        </a>
    </div>

    <form method="GET" action="{{ route('admin.users') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-bold">Filter Role</label>
            <select name="role" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Role</option>
                <option value="pegawai" {{ request('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                <option value="pasien" {{ request('role') == 'pasien' ? 'selected' : '' }}>Pasien</option>
            </select>
        </div>
        <div class="col-md-2">
            @if(request('role'))
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<div class="card-custom">

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="py-3">Nama</th>
                    <th class="py-3">Email</th>
                    <th class="py-3">Role</th>
                    <th class="py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=random&size=32" class="rounded-circle" width="32" height="32">
                            <span class="fw-bold">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @php
                            $badgeClass = match($user->role) {
                                'admin' => 'bg-dark',
                                'dokter' => 'bg-info text-dark',
                                'pasien' => 'bg-success',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} rounded-pill px-3">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.users.edit',$user->id) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Apakah anda yakin ingin menghapus user ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // Auto-dismiss alerts after 5 seconds
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    
    function autoDismissAlert(alertElement, delay = 5000) {
        if (alertElement) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }, delay);
        }
    }
    
    autoDismissAlert(successAlert);
    autoDismissAlert(errorAlert);
</script>
@endsection