@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Edit User')
@section('header-title','Edit User')
@section('header-subtitle','Ubah data akun dokter/pasien')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
@endsection

@section('content')
<h4>Form Edit User</h4>

<form action="{{ route('admin.users.update',$user->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="dokter" {{ $user->role == 'dokter' ? 'selected' : '' }}>Dokter</option>
            <option value="pasien" {{ $user->role == 'pasien' ? 'selected' : '' }}>Pasien</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('admin.users') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection