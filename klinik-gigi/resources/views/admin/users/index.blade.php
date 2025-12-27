@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Manajemen User')
@section('header-title','Manajemen User')
@section('header-subtitle','Kelola akun dokter & pasien')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link active"><i class="fa-solid fa-users"></i> Manajemen User</a>
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

<div class="d-flex justify-content-between mb-3">
    <h4>Daftar User</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah User</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>
                <a href="{{ route('admin.users.edit',$user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

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