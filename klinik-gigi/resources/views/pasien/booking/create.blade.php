@extends('layouts.dashboard')

@section('theme', 'pasien')
@section('title', 'Buat Janji Temu')
@section('header-title', 'Buat Janji Temu Baru')
@section('header-subtitle', 'Pilih jadwal yang tersedia untuk pemeriksaan Anda')

@section('sidebar-menu')
<a href="{{ route('pasien.dashboard') }}" class="nav-link {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-home"></i> Beranda</a>
<a href="{{ route('pasien.jadwal') }}" class="nav-link {{ request()->routeIs('pasien.jadwal', 'pasien.booking.create') ? 'active' : '' }}"><i class="fa-solid fa-calendar-check"></i> Jadwal Saya</a>
<a href="{{ route('pasien.rekam-medis') }}" class="nav-link {{ request()->routeIs('pasien.rekam-medis') ? 'active' : '' }}"><i class="fa-solid fa-file-medical"></i> Rekam Medis</a>
<a href="{{ route('pasien.notifications') }}" class="nav-link {{ request()->routeIs('pasien.notifications') ? 'active' : '' }}"><i class="fa-solid fa-bell"></i> Notifikasi</a>
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

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('pasien.booking.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase">Informasi Pasien</label>
                <div class="d-flex align-items-center p-3 bg-light rounded-3 border">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                        <i class="fa-solid fa-user text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $pasien->Nama }}</h6>
                        <small class="text-muted">{{ $pasien->NoTelp }}</small>
                    </div>
                </div>
                <input type="hidden" name="PasienID" value="{{ $pasien->PasienID }}">
            </div>

            <div class="mb-4">
                <label for="IdJadwal" class="form-label fw-bold">Pilih Jadwal & Dokter <span class="text-danger">*</span></label>
                <select name="IdJadwal" id="IdJadwal" class="form-select form-select-lg border-2 shadow-none" required style="border-radius: 12px;">
                    <option value="">-- Pilih Jadwal Tersedia --</option>
                    @foreach($jadwals as $jadwal)
                        <option value="{{ $jadwal->IdJadwal }}" 
                                data-sisa="{{ $jadwal->sisa_kapasitas }}"
                                data-kapasitas="{{ $jadwal->Kapasitas }}"
                                {{ old('IdJadwal') == $jadwal->IdJadwal ? 'selected' : '' }}
                                {{ $jadwal->is_full ? 'disabled' : '' }}>
                            {{ \Carbon\Carbon::parse($jadwal->Tanggal)->format('l, d M Y') }} - 
                            {{ $jadwal->dokter->Nama ?? '-' }} - 
                            {{ $jadwal->sesi }} 
                            @if($jadwal->is_full)
                                (PENUH)
                            @else
                                (Sisa Slot: {{ $jadwal->sisa_kapasitas }})
                            @endif
                        </option>
                    @endforeach
                </select>
                <div class="form-text mt-2">
                    <i class="fa-solid fa-info-circle me-1"></i> Silahkan pilih hari dan dokter yang Anda inginkan.
                </div>
            </div>

            <div class="alert alert-info border-0 bg-primary bg-opacity-10 text-primary-emphasis rounded-4 p-4 mb-4">
                <div class="d-flex gap-3">
                    <i class="fa-solid fa-clock-rotate-left mt-1" style="font-size: 1.25rem;"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Ketentuan Janji Temu:</h6>
                        <ul class="mb-0 small">
                            <li>Pasien diharapkan datang 15 menit sebelum jadwal pemeriksaan.</li>
                            <li>Jika berhalangan hadir, harap segera melakukan pembatalan jadwal.</li>
                            <li>Satu pasien hanya dapat memiliki satu janji temu aktif pada slot yang sama.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('pasien.dashboard') }}" class="btn btn-light rounded-pill px-4 order-md-1">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold order-md-2 shadow-sm">
                    Konfirmasi Janji Temu <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
