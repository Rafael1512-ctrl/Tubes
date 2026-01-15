@extends('layouts.dashboard')

@section('theme', 'pasien')
@section('title', 'Buat Janji Temu - Zenith Dental')
@section('header-title', 'Buat Janji Temu')
@section('header-subtitle', 'Pilih jadwal yang sesuai dengan waktu Anda.')

@section('sidebar-menu')
    <a href="{{ route('pasien.dashboard') }}" class="nav-link">
        <i class="fa-solid fa-house"></i> Dashboard
    </a>
    <a href="{{ route('pasien.booking.create') }}" class="nav-link active">
        <i class="fa-solid fa-calendar-plus"></i> Buat Janji Temu
    </a>
    <a href="{{ route('pasien.jadwal') }}" class="nav-link">
        <i class="fa-solid fa-calendar-days"></i> Jadwal & Riwayat
    </a>
    <a href="{{ route('pasien.rekam-medis') }}" class="nav-link">
        <i class="fa-solid fa-file-medical"></i> Rekam Medis
    </a>
    <a href="{{ route('pasien.notifications') }}" class="nav-link">
        <i class="fa-solid fa-bell"></i> Notifikasi
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        {{-- Alert untuk validation errors --}}
        @if($errors->any())
        <div class="alert alert-warning alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-start">
                <i class="fa-solid fa-triangle-exclamation me-3 h4 mb-0"></i>
                <div>
                    <h6 class="fw-bold mb-1">Perhatian!</h6>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card-custom bg-white border-0 shadow-sm overflow-hidden p-0">
            <div class="bg-primary bg-opacity-10 p-4 border-bottom">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-4 me-3">
                        <i class="fa-solid fa-calendar-check fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Form Reservasi</h5>
                        <p class="text-muted small mb-0">Silahkan lengkapi detail kunjungan Anda</p>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <form action="{{ route('pasien.booking.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Data Pasien</label>
                        <div class="d-flex align-items-center p-3 bg-light rounded-4 border border-light-subtle">
                            <img src="https://ui-avatars.com/api/?name={{ $pasien->Nama }}&background=0ea5e9&color=fff" class="rounded-circle me-3" width="45">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $pasien->Nama }}</h6>
                                <small class="text-muted">{{ $pasien->NoTelp }}</small>
                            </div>
                        </div>
                        <input type="hidden" name="PasienID" value="{{ $pasien->PasienID }}">
                    </div>

                    <div class="mb-4">
                        <label for="IdJadwal" class="form-label fw-bold">Pilih Jadwal & Dokter <span class="text-danger">*</span></label>
                        <select name="IdJadwal" id="IdJadwal" class="form-select form-select-lg border-2 shadow-none transition-all" required style="border-radius: 12px; font-size: 1rem;">
                            <option value="">-- Pilih Jadwal Tersedia --</option>
                            @foreach($jadwals as $jadwal)
                                <option value="{{ $jadwal->IdJadwal }}" 
                                        data-sisa="{{ $jadwal->sisa_kapasitas }}"
                                        data-kapasitas="{{ $jadwal->Kapasitas }}"
                                        {{ old('IdJadwal') == $jadwal->IdJadwal ? 'selected' : '' }}
                                        {{ $jadwal->is_full ? 'disabled' : '' }}>
                                    {{ \Carbon\Carbon::parse($jadwal->Tanggal)->format('l, d M Y') }} | 
                                    {{ $jadwal->dokter->Nama ?? '-' }} | 
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
                            <i class="fa-solid fa-info-circle me-1 text-primary"></i> Pilih jadwal yang sesuai dengan waktu luang Anda.
                        </div>
                    </div>

                    <div class="p-4 rounded-4 mb-4" style="background: var(--primary-soft); color: var(--primary-dark);">
                        <div class="d-flex gap-3">
                            <i class="fa-solid fa-shield-halved mt-1 h5 mb-0"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Informasi Penting:</h6>
                                <ul class="mb-0 small">
                                    <li>Harap hadir 15 menit sebelum waktu pemeriksaan dimulai.</li>
                                    <li>Pembatalan dapat dilakukan maksimal 2 jam sebelum jadwal.</li>
                                    <li>Tunjukkan kartu pasien atau identitas saat registrasi ulang.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-md-end">
                        <a href="{{ route('pasien.dashboard') }}" class="btn btn-light rounded-pill px-4 fw-semibold order-2 order-md-1">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm order-1 order-md-2" style="background: var(--gradient-primary); border: none;">
                            Konfirmasi Booking <i class="fa-solid fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25rem rgba(14, 165, 233, 0.1);
    }
</style>
@endsection
