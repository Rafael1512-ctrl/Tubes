@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Riwayat Rekam Medis')
@section('header-title','Detail Rekam Medis')
@section('header-subtitle','Pasien: ' . $pasien->Nama)

@section('sidebar-menu')
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('admin.booking') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.pasien') }}" class="nav-link active"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
<a href="{{ route('admin.obat') }}" class="nav-link"><i class="fa-solid fa-pills"></i> Data Obat</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
<a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 2.5rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 2.5rem;
    }
    .timeline-dot {
        position: absolute;
        left: -2.15rem;
        top: 0.25rem;
        width: 0.85rem;
        height: 0.85rem;
        border-radius: 50%;
        background: var(--primary);
        border: 2px solid white;
        box-shadow: 0 0 0 2px var(--primary);
    }
    .rm-card {
        border-radius: 15px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.pasien') }}" class="btn btn-light btn-sm rounded-pill px-3 border">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Pasien
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <!-- Patient Info Card -->
        <div class="card-custom text-center p-4">
            <div class="avatar-container mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 70px; height: 70px; font-size: 1.5rem; font-weight: 700;">
                    {{ substr($pasien->Nama, 0, 1) }}
                </div>
            </div>
            <h5 class="fw-bold mb-1">{{ $pasien->Nama }}</h5>
            <code class="text-primary mb-3 d-block">{{ $pasien->PasienID }}</code>
            
            <div class="text-start border-top pt-3 mt-3">
                <div class="mb-2 d-flex justify-content-between">
                    <small class="text-muted">Jenis Kelamin</small>
                    <small class="fw-bold text-dark">{{ $pasien->JenisKelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</small>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <small class="text-muted">Usia</small>
                    <small class="fw-bold text-dark">{{ $pasien->TanggalLahir ? \Carbon\Carbon::parse($pasien->TanggalLahir)->age : '-' }} Tahun</small>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <small class="text-muted">No. Telepon</small>
                    <small class="fw-bold text-dark">{{ $pasien->NoTelp }}</small>
                </div>
                <div class="mb-0">
                    <small class="text-muted d-block mb-1">Alamat</small>
                    <small class="fw-bold text-dark">{{ $pasien->Alamat }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-notes-medical me-2 text-primary"></i>Riwayat Pemeriksaan</h5>
                <span class="badge bg-light text-dark border">{{ $pasien->rekamMedis->count() }} Kunjungan</span>
            </div>

            @if($pasien->rekamMedis->count() > 0)
                <div class="timeline">
                    @foreach($pasien->rekamMedis->sortByDesc('Tanggal') as $rm)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="card rm-card">
                                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($rm->Tanggal)->isoFormat('dddd, D MMMM YYYY') }}</div>
                                        <small class="text-muted"><i class="fa-solid fa-user-doctor me-1"></i> Dokter: {{ $rm->dokter->Nama ?? '-' }}</small>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary border-0 rounded-pill px-3">{{ $rm->IdRekamMedis }}</span>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="p-3 bg-light rounded-3 mb-3">
                                        <label class="fw-bold small text-uppercase text-muted d-block mb-1">Diagnosa Utama</label>
                                        <p class="mb-0 fw-semibold">{{ $rm->Diagnosa }}</p>
                                    </div>
                                    
                                    @if($rm->Catatan)
                                    <div class="mb-3">
                                        <label class="fw-bold small text-uppercase text-muted d-block mb-1">Catatan Tambahan</label>
                                        <p class="mb-0 small text-muted">{{ $rm->Catatan }}</p>
                                    </div>
                                    @endif

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="border rounded-3 p-2">
                                                <label class="fw-bold small text-uppercase text-muted d-block mb-2 border-bottom pb-1">Tindakan</label>
                                                <ul class="list-unstyled mb-0">
                                                    @forelse($rm->tindakan as $t)
                                                        <li class="small mb-1"><i class="fa-solid fa-circle-check text-success me-2"></i>{{ $t->NamaTindakan }}</li>
                                                    @empty
                                                        <li class="small text-muted italic">Tidak ada tindakan recorded</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border rounded-3 p-2">
                                                <label class="fw-bold small text-uppercase text-muted d-block mb-2 border-bottom pb-1">Resep Obat</label>
                                                <ul class="list-unstyled mb-0">
                                                    @forelse($rm->obat as $o)
                                                        <li class="small mb-1"><i class="fa-solid fa-pills text-info me-2"></i>{{ $o->NamaObat }} ({{ $o->pivot->Dosis }})</li>
                                                    @empty
                                                        <li class="small text-muted italic">Tidak ada resep recorded</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa-solid fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
                    <p class="text-muted">Belum ada riwayat rekam medis untuk pasien ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
