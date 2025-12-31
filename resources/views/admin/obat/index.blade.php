@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Manajemen Data Obat')
@section('header-title','Inventory Obat')
@section('header-subtitle','Kelola stok dan harga obat klinik')

@section('sidebar-menu')
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('admin.booking') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.pasien') }}" class="nav-link"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
<a href="{{ route('admin.obat') }}" class="nav-link active"><i class="fa-solid fa-pills"></i> Data Obat</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
<a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Berhasil!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card-custom mb-4">
    <form action="{{ route('admin.obat') }}" method="GET" class="row g-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control rounded-pill px-3" placeholder="Cari nama atau kode obat..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="jenis" class="form-select rounded-pill px-3">
                <option value="">Semua Jenis</option>
                @foreach($jenisObats as $j)
                    <option value="{{ $j->JenisObatID }}" {{ request('jenis') == $j->JenisObatID ? 'selected' : '' }}>{{ $j->NamaJenis }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary rounded-pill w-100"><i class="fa-solid fa-search me-1"></i> Filter</button>
        </div>
        <div class="col-md-3 text-end">
            <a href="{{ route('admin.obat.create') }}" class="btn btn-success rounded-pill px-4"><i class="fa-solid fa-plus me-1"></i> Tambah Obat</a>
        </div>
    </form>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Obat</th>
                    <th>Jenis</th>
                    <th>Satuan</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($obats as $obat)
                <tr>
                    <td><code>{{ $obat->IdObat }}</code></td>
                    <td><div class="fw-bold">{{ $obat->NamaObat }}</div></td>
                    <td><span class="badge bg-info-subtle text-info">{{ $obat->jenisObat->NamaJenis ?? '-' }}</span></td>
                    <td>{{ $obat->Satuan }}</td>
                    <td class="text-muted small">Rp {{ number_format($obat->HargaBeli ?? 0, 0, ',', '.') }}</td>
                    <td class="fw-bold text-primary">Rp {{ number_format($obat->HargaJual ?? $obat->Harga, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @php
                            $stokClass = 'success';
                            if($obat->Stok <= 10) $stokClass = 'danger';
                            elseif($obat->Stok <= 30) $stokClass = 'warning';
                        @endphp
                        <span class="badge bg-{{ $stokClass }}-subtle text-{{ $stokClass }} px-3 py-2 rounded-pill">
                            {{ $obat->Stok }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.obat.edit', $obat->IdObat) }}" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px;"><i class="fa-solid fa-pen p-1"></i></a>
                            <form action="{{ route('admin.obat.destroy', $obat->IdObat) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus obat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px;"><i class="fa-solid fa-trash p-1"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">Data obat tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $obats->links() }}
    </div>
</div>

@endsection
