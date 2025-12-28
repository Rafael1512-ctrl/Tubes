@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Edit Data Obat')
@section('header-title','Edit Data Obat')
@section('header-subtitle','Perbarui stok atau harga obat terpilih')

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

<div class="mb-4">
    <a href="{{ route('admin.obat') }}" class="btn btn-light btn-sm rounded-pill px-3 border">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Inventory
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Form Edit Obat</h5>
                <span class="badge bg-light text-dark">ID: {{ $obat->IdObat }}</span>
            </div>

            @if($errors->any())
            <div class="alert alert-danger px-3 py-2 small mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.obat.update', $obat->IdObat) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">Jenis Obat <span class="text-danger">*</span></label>
                    <select name="IdJenisObat" class="form-select rounded-3 p-2" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($jenisObats as $j)
                            <option value="{{ $j->JenisObatID }}" {{ $obat->IdJenisObat == $j->JenisObatID ? 'selected' : '' }}>{{ $j->NamaJenis }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">Nama Obat <span class="text-danger">*</span></label>
                    <input type="text" name="NamaObat" class="form-control rounded-3 p-2" placeholder="Contoh: Paracetamol 500mg" value="{{ old('NamaObat', $obat->NamaObat) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="Satuan" class="form-control rounded-3 p-2" placeholder="Tablet, Botol, Kapsul..." value="{{ old('Satuan', $obat->Satuan) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Stok Saat Ini <span class="text-danger">*</span></label>
                        <input type="number" name="Stok" class="form-control rounded-3 p-2" value="{{ old('Stok', $obat->Stok) }}" min="0" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted text-uppercase">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">Rp</span>
                        <input type="number" name="Harga" class="form-control rounded-end-3 p-2" placeholder="0" value="{{ old('Harga', (int)$obat->Harga) }}" min="0" required>
                    </div>
                </div>

                <div class="d-grid mt-2">
                    <button type="submit" class="btn btn-primary rounded-pill p-2 fw-bold">
                        <i class="fa-solid fa-save me-2"></i> Perbarui Data Obat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
