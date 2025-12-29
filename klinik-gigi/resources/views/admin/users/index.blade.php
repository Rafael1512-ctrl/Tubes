@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Manajemen User')
@section('header-title','Manajemen User')
@section('header-subtitle','Kelola akun dokter, staf, dan pasien')

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
    /* Table Styles */
    .table-container {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    .table { margin-bottom: 0; }
    
    .table thead th {
        background: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem;
        white-space: nowrap;
        color: #495057;
    }

    .table tbody tr { transition: all 0.2s; }
    .table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.002);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: #6c757d;
    }
    
    .table tbody td .fw-bold {
        color: #343a40;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Badge Styling */
    .badge-role {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Animations */
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-down { animation: slideDown 0.4s ease-out; }
</style>
@endsection

@section('content')

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-slide-down shadow-sm border-0 mb-4" role="alert" id="success-alert">
    <div class="d-flex align-items-center">
        <div class="bg-success text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa-solid fa-check"></i></div>
        <div><strong>Berhasil!</strong> {{ session('success') }}</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" id="error-alert">
    <div class="d-flex align-items-center">
         <div class="bg-danger text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa-solid fa-xmark"></i></div>
        <div><strong>Gagal!</strong> {{ session('error') }}</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="filter-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="m-0 fw-bold text-dark"><i class="fa-solid fa-sliders me-2 text-primary"></i> Filter & Aksi</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-2"></i>Tambah User Baru
        </a>
    </div>

    <form method="GET" action="{{ route('admin.users') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-bold">Filter berdasarkan Role</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user-tag text-muted"></i></span>
                <select name="role" class="form-select border-start-0" onchange="this.form.submit()">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="dokter" {{ request('role') == 'dokter' ? 'selected' : '' }}>Dokter</option>
                    <option value="pasien" {{ request('role') == 'pasien' ? 'selected' : '' }}>Pasien</option>
                    <option value="pegawai" {{ request('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            @if(request('role'))
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary w-100">
                    <i class="fa-solid fa-rotate-right me-2"></i>Reset
                </a>
            @endif
        </div>
    </form>
</div>

<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-dark">
            <i class="fa-solid fa-users-gear me-2 text-primary"></i>
            Daftar Pengguna Sistem
        </h4>
        <div class="text-muted small">
            Menampilkan semua akun terdaftar
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="ps-4">Nama Pengguna</th>
                    <th>Email</th>
                    <th>Hak Akses / Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=40&color=fff&bold=true" 
                                 class="rounded-circle me-3 border shadow-sm" width="40" height="40">
                            <div>
                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                                <small class="text-muted">UID: #{{ $user->id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="fa-regular fa-envelope me-2 text-primary opacity-50"></i>
                            <span>{{ $user->email }}</span>
                        </div>
                    </td>
                    <td>
                        @php
                            $roleData = match($user->role) {
                                'admin' => ['class' => 'dark', 'icon' => 'fa-user-shield'],
                                'dokter' => ['class' => 'info', 'icon' => 'fa-user-md'],
                                'pasien' => ['class' => 'success', 'icon' => 'fa-hospital-user'],
                                'pegawai' => ['class' => 'warning', 'icon' => 'fa-id-badge'],
                                default => ['class' => 'secondary', 'icon' => 'fa-user']
                            };
                        @endphp
                        <span class="badge-role bg-{{ $roleData['class'] }} bg-opacity-10 text-{{ $roleData['class'] }} border border-{{ $roleData['class'] }} border-opacity-25">
                            <i class="fa-solid {{ $roleData['icon'] }} me-1"></i>{{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.users.edit',$user->id) }}" 
                               class="btn btn-primary btn-sm rounded-circle" title="Edit User"
                               style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin menghapus user ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm rounded-circle" title="Hapus User"
                                        style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // Auto-dismiss alerts
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