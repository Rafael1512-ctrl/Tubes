@extends('layouts.dashboard')

@section('theme', 'pasien')
@section('title', 'Detail Rekam Medis - Zenith Dental')
@section('header-title', 'Detail Pemeriksaan')
@section('header-subtitle', 'Informasi lengkap hasil kunjungan Anda.')

@section('sidebar-menu')
    <a href="{{ route('pasien.dashboard') }}" class="nav-link">
        <i class="fa-solid fa-house"></i> Dashboard
    </a>
    <a href="{{ route('pasien.booking.create') }}" class="nav-link">
        <i class="fa-solid fa-calendar-plus"></i> Buat Janji Temu
    </a>
    <a href="{{ route('pasien.jadwal') }}" class="nav-link">
        <i class="fa-solid fa-calendar-days"></i> Jadwal & Riwayat
    </a>
    <a href="{{ route('pasien.rekam-medis') }}" class="nav-link active">
        <i class="fa-solid fa-file-medical"></i> Rekam Medis
    </a>
    <a href="{{ route('pasien.notifications') }}" class="nav-link">
        <i class="fa-solid fa-bell"></i> Notifikasi
    </a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Main Info Card -->
        <div class="card-custom bg-white border-0 shadow-sm overflow-hidden p-0 mb-4">
            <div class="bg-primary bg-opacity-10 p-4 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-white p-3 rounded-4 shadow-sm text-primary me-3">
                        <i class="fa-solid fa-notes-medical fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">No. RM: {{ $history->IdRekamMedis }}</h5>
                        <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($history->Tanggal)->format('l, d F Y') }}</p>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge bg-success rounded-pill px-3 py-2">Selesai</span>
                </div>
            </div>
            
            <div class="p-4">
                <div class="mb-5">
                    <h6 class="fw-bold text-muted text-uppercase small mb-3 border-start border-primary border-4 ps-2">Informasi Pemeriksaan</h6>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small text-muted mb-1">Keluhan Utama</label>
                            <p class="fw-semibold text-dark">{{ $history->Keluhan }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted mb-1">Diagnosa</label>
                            <p class="fw-semibold text-dark">{{ $history->Diagnosa }}</p>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted mb-1">Catatan Dokter</label>
                            <div class="p-3 bg-light rounded-4 border border-light-subtle italic">
                                "{{ $history->Catatan ?? 'Tidak ada catatan tambahan.' }}"
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h6 class="fw-bold text-muted text-uppercase small mb-3 border-start border-secondary border-4 ps-2">Tindakan Medis</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="bg-light rounded-4">
                                <tr>
                                    <th class="ps-3 py-2 small fw-bold">Nama Tindakan</th>
                                    <th class="py-2 small fw-bold text-end pe-3">Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history->tindakan as $t)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold text-dark">{{ $t->NamaTindakan }}</div>
                                    </td>
                                    <td class="text-end pe-3 text-muted">
                                        Rp {{ number_format($t->pivot->Harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-2">
                    <h6 class="fw-bold text-muted text-uppercase small mb-3 border-start border-accent border-4 ps-2">Obat / Resep</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="bg-light rounded-4">
                                <tr>
                                    <th class="ps-3 py-2 small fw-bold">Nama Obat</th>
                                    <th class="py-2 small fw-bold text-center">Dosis</th>
                                    <th class="py-2 small fw-bold text-center">Durasi</th>
                                    <th class="py-2 small fw-bold text-end pe-3">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history->obat as $o)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold text-dark">{{ $o->NamaObat }}</div>
                                    </td>
                                    <td class="text-center small text-muted">
                                        {{ $o->pivot->Dosis }} x {{ $o->pivot->Frekuensi }}
                                    </td>
                                    <td class="text-center small text-muted">
                                        {{ $o->pivot->LamaHari }} Hari
                                    </td>
                                    <td class="text-end pe-3 fw-bold text-primary">
                                        {{ $o->pivot->Jumlah }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted small py-3 italic">Tidak ada resep obat.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('pasien.rekam-medis') }}" class="btn btn-light rounded-pill px-4 fw-bold">
            <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="col-lg-4">
        <!-- Doctor Info -->
        <div class="card-custom bg-white border-0 shadow-sm p-4 mb-4">
            <h6 class="fw-bold text-muted text-uppercase small mb-4">Dokter Pemeriksa</h6>
            <div class="text-center py-2">
                <img src="https://ui-avatars.com/api/?name={{ $history->dokter->Nama ?? 'Dr' }}&background=0ea5e9&color=fff&size=128" class="rounded-circle shadow-sm mb-3" width="100">
                <h5 class="fw-bold mb-1">{{ $history->dokter->Nama ?? '-' }}</h5>
                <p class="text-primary small fw-bold mb-0">{{ $history->dokter->Spesialisasi ?? 'Dokter Gigi' }}</p>
            </div>
            <hr class="my-4 opacity-50">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-light p-2 rounded-circle text-muted">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
                <div>
                    <small class="text-muted d-block">SIP / ID Pegawai</small>
                    <span class="small fw-bold">{{ $history->DokterID }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Summary if any -->
        @if($history->pembayaran)
        <div class="card-custom border-0 shadow-sm p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
            <h6 class="fw-bold opacity-75 text-uppercase small mb-4">Ringkasan Biaya</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="small opacity-75">Tindakan & Obat</span>
                <span class="small fw-bold">Rp {{ number_format($history->pembayaran->TotalBayar, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-4">
                <span class="small opacity-75">Status Bayar</span>
                <span class="badge {{ $history->pembayaran->Status == 'PAID' ? 'bg-success' : 'bg-warning' }} rounded-pill">{{ $history->pembayaran->Status }}</span>
            </div>
            <div class="text-center pt-2 border-top border-white border-opacity-10">
                <small class="opacity-50 italic">Kwitansi telah dikirimkan ke email Anda.</small>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
