@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Manajemen User')
@section('header-title','Manajemen User')
@section('header-subtitle','Kelola akun dokter & pasien')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link active"><i class="fa-solid fa-users"></i> Manajemen User</a>
@endsection

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Daftar User</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah User</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

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
@endsection