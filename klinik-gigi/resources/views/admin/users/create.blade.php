@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Tambah Pegawai')
@section('header-title','Tambah Pegawai')
@section('header-subtitle','Buat akun pegawai baru')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.pegawai') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen Pegawai</a>
@endsection

@section('content')
<h4>Form Tambah Pegawai</h4>

<form action="{{ route('admin.pegawai.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Jabatan</label>
        <select name="jabatan" class="form-select" required>
            <option value="admin">Admin</option>
            <option value="dokter">Dokter</option>
            <option value="resepsionis">Resepsionis</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Tanggal Masuk</label>
        <input type="date" name="tanggal_masuk" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">No. Telp</label>
        <input type="text" name="no_telp" class="form-control" required>
    </div>
    <hr>
    <h5>Akun Login</h5>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.pegawai') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection