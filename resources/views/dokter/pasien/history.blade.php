@extends('layouts.dashboard')

@section('theme','dokter')
@section('title','Riwayat Rekam Medis')
@section('header-title','Riwayat Rekam Medis')
@section('header-subtitle','Pasien: ' . $pasien->Nama)

@section('sidebar-menu')
<a href="/dokter/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('dokter.jadwal') }}" class="nav-link"><i class="fa-solid fa-calendar-week"></i> Jadwal Saya</a>
<a href="{{ route('dokter.pasien') }}" class="nav-link active"><i class="fa-solid fa-user-injured"></i> Data Pasien</a>
<a href="{{ route('dokter.riwayat') }}" class="nav-link"><i class="fa-solid fa-history"></i> Riwayat Praktek</a>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 3rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }
    .timeline-dot {
        position: absolute;
        left: -2.65rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #0d6efd;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #0d6efd;
    }
</style>
@endsection

@section('content')

<div class="mb-4">
    <a href="{{ route('dokter.pasien') }}" class="btn btn-light border btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Pasien
    </a>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- Patient Summary Card -->
        <div class="card-custom mb-4">
            <h6 class="fw-bold mb-3">Data Pasien</h6>
            <div class="text-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($pasien->Nama, 0, 1) }}
                </div>
                <h5 class="fw-bold mt-2 mb-0">{{ $pasien->Nama }}</h5>
                <small class="text-muted">{{ $pasien->PasienID }}</small>
            </div>
            <hr>
            <div class="small">
                <div class="mb-2"><strong>Gender:</strong> {{ $pasien->JenisKelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                <div class="mb-2"><strong>Usia:</strong> {{ $pasien->TanggalLahir ? \Carbon\Carbon::parse($pasien->TanggalLahir)->age : '-' }} Tahun</div>
                <div class="mb-2"><strong>Telepon:</strong> {{ $pasien->NoTelp }}</div>
                <div class="mb-2"><strong>Alamat:</strong> {{ $pasien->Alamat }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-custom">
            <h5 class="fw-bold mb-4">Riwayat Pemeriksaan</h5>

            @if($pasien->rekamMedis->count() > 0)
                <div class="timeline">
                    @foreach($pasien->rekamMedis->sortByDesc('Tanggal') as $rm)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($rm->Tanggal)->isoFormat('D MMMM YYYY') }}</span>
                                        <span class="badge bg-secondary ms-2">{{ $rm->IdRekamMedis }}</span>
                                    </div>
                                    <small class="text-muted">Oleh: {{ $rm->dokter->Nama ?? '-' }}</small>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="fw-bold small text-uppercase text-primary">Diagnosa</label>
                                        <p class="mb-0">{{ $rm->Diagnosa }}</p>
                                    </div>
                                    
                                    @if($rm->Catatan)
                                    <div class="mb-3">
                                        <label class="fw-bold small text-uppercase text-muted">Catatan</label>
                                        <p class="mb-0">{{ $rm->Catatan }}</p>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="fw-bold small text-uppercase text-muted">Tindakan</label>
                                            <ul class="list-unstyled mb-0">
                                                @forelse($rm->tindakan as $t)
                                                    <li><i class="fa-solid fa-check-circle text-success me-1"></i> {{ $t->NamaTindakan }}</li>
                                                @empty
                                                    <li class="small text-muted">Tidak ada tindakan</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold small text-uppercase text-muted">Obat (Resep)</label>
                                            <ul class="list-unstyled mb-0">
                                                @forelse($rm->obat as $o)
                                                    <li><i class="fa-solid fa-pills text-info me-1"></i> {{ $o->NamaObat }} ({{ $o->pivot->Dosis }})</li>
                                                @empty
                                                    <li class="small text-muted">Tidak ada resep obat</li>
                                                @endforelse
                                            </ul>
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
