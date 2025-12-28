@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Tambah Booking')
@section('header-title','Tambah Booking Pasien')
@section('header-subtitle','Buat booking baru untuk pasien')

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link active"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
    <a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
    <a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('content')

{{-- Alert untuk validation errors --}}
@if($errors->any())
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-start">
        <i class="fa-solid fa-triangle-exclamation me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Perhatian!</strong>
            <p class="mb-2">Terdapat kesalahan pada input:</p>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
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

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.booking.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="PasienID" class="form-label fw-bold">Pilih Pasien <span class="text-danger">*</span></label>
                <select name="PasienID" id="PasienID" class="form-select" required>
                    <option value="">-- Pilih Pasien --</option>
                    @foreach($pasiens as $pasien)
                        <option value="{{ $pasien->PasienID }}" {{ old('PasienID') == $pasien->PasienID ? 'selected' : '' }}>
                            {{ $pasien->Nama }} - {{ $pasien->NoTelp ?? 'No Telp tidak tersedia' }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Pilih pasien yang akan melakukan booking</small>
            </div>

            <div class="mb-3">
                <label for="IdJadwal" class="form-label fw-bold">Pilih Jadwal <span class="text-danger">*</span></label>
                <select name="IdJadwal" id="IdJadwal" class="form-select" required>
                    <option value="">-- Pilih Jadwal --</option>
                    @foreach($jadwals as $jadwal)
                        <option value="{{ $jadwal->IdJadwal }}" 
                                data-sisa="{{ $jadwal->sisa_kapasitas }}"
                                data-kapasitas="{{ $jadwal->Kapasitas }}"
                                {{ old('IdJadwal') == $jadwal->IdJadwal ? 'selected' : '' }}
                                {{ $jadwal->is_full ? 'disabled' : '' }}>
                            {{ $jadwal->formatted_tanggal }} - 
                            {{ $jadwal->dokter->Nama ?? '-' }} - 
                            {{ $jadwal->sesi }} 
                            (Sisa: {{ $jadwal->sisa_kapasitas }}/{{ $jadwal->Kapasitas }})
                            {{ $jadwal->is_full ? '- PENUH' : '' }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Hanya jadwal yang available dan masih ada slot yang ditampilkan</small>
            </div>

            <div class="mb-3">
                <label for="Status" class="form-label fw-bold">Status</label>
                <select name="Status" id="Status" class="form-select">
                    <option value="PRESENT" {{ old('Status') == 'PRESENT' ? 'selected' : '' }}>Present</option>
                    <option value="CANCELLED" {{ old('Status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i>
                <strong>Informasi:</strong>
                <ul class="mb-0 mt-2">
                    <li>Tanggal booking akan diset otomatis ke waktu sekarang</li>
                    <li>Pastikan pasien belum memiliki booking aktif pada jadwal yang sama</li>
                    <li>Kapasitas jadwal akan dicek otomatis oleh sistem</li>
                </ul>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-save"></i> Simpan Booking
                </button>
                <a href="{{ route('admin.booking') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
