@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Tambah Jadwal Dokter')
@section('header-title','Tambah Jadwal Dokter')
@section('header-subtitle','Buat jadwal praktek dokter baru')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link active"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
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
                            Pagi (09:00 - 12:00)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Sesi" id="sesi_sore" 
                               value="sore" {{ old('Sesi') == 'sore' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="sesi_sore">
                            Sore (17:00 - 20:00)
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="Status" class="form-label fw-bold">Status</label>
                <select name="Status" id="Status" class="form-select">
                    <option value="Available" {{ old('Status') == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Cancelled" {{ old('Status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i>
                <strong>Informasi Kapasitas:</strong>
                <ul class="mb-0 mt-2">
                    <li><strong>Dokter Gigi:</strong> 15 pasien</li>
                    <li><strong>Dokter Spesialis:</strong> 4 pasien</li>
                </ul>
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

<style>
    /* Styling optional jika user menginginkan sedikit touch-up tapi tetap simple */
    .form-label {
        color: #495057;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalInput = document.getElementById('Tanggal');
    const pagiRadio = document.getElementById('sesi_pagi');
    const soreRadio = document.getElementById('sesi_sore');

    function checkSessions() {
        const today = new Date().toISOString().split('T')[0];
        const selectedDate = tanggalInput.value;
        const now = new Date();
        const currentHour = now.getHours();

        if (selectedDate === today) {
            // Pagi: berakhir 12:00, batas 11:00
            if (currentHour >= 11) {
                pagiRadio.disabled = true;
                if (pagiRadio.checked) {
                    pagiRadio.checked = false;
                    soreRadio.checked = true;
                }
            } else {
                pagiRadio.disabled = false;
            }

            // Sore: berakhir 20:00, batas 19:00
            if (currentHour >= 19) {
                soreRadio.disabled = true;
                if (soreRadio.checked) {
                    soreRadio.checked = false;
                    alert('Semua sesi untuk hari ini sudah mencapai batas waktu booking (1 jam sebelum berakhir). Silakan pilih tanggal lain.');
                }
            } else {
                soreRadio.disabled = false;
            }
        } else {
            pagiRadio.disabled = false;
            soreRadio.disabled = false;
        }
    }

    tanggalInput.addEventListener('change', checkSessions);
    checkSessions(); // Run on load
});
</script>
@endpush

@endsection
