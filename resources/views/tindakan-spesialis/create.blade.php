@extends('layouts.app')

@section('title', 'Tambah Kontrol Berkala')

@section('content')
<div class="container">
    <div class="mb-4">
        <h2 class="text-secondary fw-bold">Buat Kontrol Berkala Baru</h2>
        <p class="text-muted">Atur jadwal pertemuan berkala untuk tindakan spesialis</p>
    </div>

    <div class="card-custom">
        <div class="card-header-custom">Form Kontrol Berkala</div>
        <div class="p-4">
            <form action="{{ route('tindakan-spesialis.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Pasien <span class="text-danger">*</span></label>
                        <select name="PasienID" class="form-select @error('PasienID') is-invalid @enderror" required>
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($pasiens as $pasien)
                                <option value="{{ $pasien->PasienID }}" {{ old('PasienID') == $pasien->PasienID ? 'selected' : '' }}>
                                    {{ $pasien->Nama }} ({{ $pasien->PasienID }})
                                </option>
                            @endforeach
                        </select>
                        @error('PasienID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Dokter <span class="text-danger">*</span></label>
                        <select name="DokterID" class="form-select @error('DokterID') is-invalid @enderror" required>
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($dokters as $dokter)
                                <option value="{{ $dokter->PegawaiID }}" {{ old('DokterID') == $dokter->PegawaiID ? 'selected' : '' }}>
                                    {{ $dokter->Nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('DokterID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Tindakan <span class="text-danger">*</span></label>
                    <input type="text" name="NamaTindakan" class="form-control @error('NamaTindakan') is-invalid @enderror" 
                           value="{{ old('NamaTindakan') }}" placeholder="Contoh: Perawatan Ortodonti" required>
                    @error('NamaTindakan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Frekuensi <span class="text-danger">*</span></label>
                        <select name="frequency" id="frequency" class="form-select @error('frequency') is-invalid @enderror" required>
                            <option value="">-- Pilih Frekuensi --</option>
                            <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Mingguan (Weekly)</option>
                            <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Bulanan (Monthly)</option>
                            <option value="custom" {{ old('frequency') == 'custom' ? 'selected' : '' }}>Kustom</option>
                        </select>
                        @error('frequency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3" id="custom_days_field" style="display: none;">
                        <label class="form-label fw-bold">Jumlah Hari (Kustom)</label>
                        <input type="number" name="custom_days" class="form-control @error('custom_days') is-invalid @enderror" 
                               value="{{ old('custom_days', 7) }}" min="1" max="365" placeholder="7">
                        <small class="text-muted">Jarak hari antar sesi</small>
                        @error('custom_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Total Sesi <span class="text-danger">*</span></label>
                        <input type="number" name="total_sessions" class="form-control @error('total_sessions') is-invalid @enderror" 
                               value="{{ old('total_sessions', 4) }}" min="1" max="52" placeholder="4" required>
                        <small class="text-muted">Maksimal 52 sesi</small>
                        @error('total_sessions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                               value="{{ old('start_date', date('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Rencana/Tujuan</label>
                    <textarea name="plan_goal" class="form-control @error('plan_goal') is-invalid @enderror" 
                              rows="3" placeholder="Deskripsi rencana perawatan...">{{ old('plan_goal') }}</textarea>
                    @error('plan_goal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi:</strong> Sistem akan otomatis membuat jadwal untuk semua sesi dan generate reminder H-3 dan H-1 untuk setiap sesi.
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Buat Jadwal Kontrol Berkala
                    </button>
                    <a href="{{ route('tindakan-spesialis.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('frequency').addEventListener('change', function() {
        const customField = document.getElementById('custom_days_field');
        if (this.value === 'custom') {
            customField.style.display = 'block';
        } else {
            customField.style.display = 'none';
        }
    });
    
    // Trigger on page load if old value is custom
    if (document.getElementById('frequency').value === 'custom') {
        document.getElementById('custom_days_field').style.display = 'block';
    }
</script>
@endpush
@endsection
