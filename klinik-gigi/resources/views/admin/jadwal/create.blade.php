@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Tambah Jadwal Dokter')
@section('header-title','Tambah Jadwal Dokter')
@section('header-subtitle','Buat jadwal praktek dokter baru')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link active"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
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

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.jadwal.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="IdDokter" class="form-label fw-bold">Pilih Dokter <span class="text-danger">*</span></label>
                <select name="IdDokter" id="IdDokter" class="form-select" required>
                    <option value="">-- Pilih Dokter --</option>
                    @foreach($dokters as $dokter)
                        <option value="{{ $dokter->PegawaiID }}" 
                                data-jabatan="{{ $dokter->Jabatan }}"
                                {{ old('IdDokter') == $dokter->PegawaiID ? 'selected' : '' }}>
                            {{ $dokter->Nama }} - {{ ucfirst($dokter->Jabatan) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="Tanggal" class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="Tanggal" id="Tanggal" class="form-control" 
                       value="{{ old('Tanggal') }}" min="{{ date('Y-m-d') }}" required>
                <small class="text-muted">Tanggal tidak boleh di masa lalu</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Sesi <span class="text-danger">*</span></label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Sesi" id="sesi_pagi" 
                               value="pagi" {{ old('Sesi') == 'pagi' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="sesi_pagi">
                            <i class="fa-solid fa-sun text-warning"></i> Pagi (09:00 - 12:00)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Sesi" id="sesi_sore" 
                               value="sore" {{ old('Sesi') == 'sore' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="sesi_sore">
                            <i class="fa-solid fa-moon text-primary"></i> Sore (17:00 - 20:00)
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Kapasitas (Otomatis)</label>
                <div class="alert alert-info" id="kapasitas-info">
                    <i class="fa-solid fa-info-circle"></i>
                    Kapasitas akan ditentukan otomatis berdasarkan jabatan dokter:
                    <ul class="mb-0 mt-2">
                        <li><strong>Dokter Gigi:</strong> 15 pasien</li>
                        <li><strong>Dokter Spesialis:</strong> 4 pasien</li>
                    </ul>
                </div>
            </div>

            <div class="mb-3">
                <label for="Status" class="form-label fw-bold">Status</label>
                <select name="Status" id="Status" class="form-select">
                    <option value="Available" {{ old('Status') == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Cancelled" {{ old('Status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-save"></i> Simpan Jadwal
                </button>
                <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
