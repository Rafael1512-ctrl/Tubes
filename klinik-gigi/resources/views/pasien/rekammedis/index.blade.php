@extends('layouts.dashboard')

@section('theme', 'pasien')
@section('title', 'Rekam Medis - Zenith Dental')
@section('header-title', 'Rekam Medis Saya')
@section('header-subtitle', 'Lihat riwayat kesehatan gigi dan tindakan yang pernah dilakukan.')

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
    <!-- Filter Card -->
    <div class="col-12">
        <div class="card-custom bg-white border-0 shadow-sm p-4">
            <form action="{{ route('pasien.rekam-medis') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="dari" class="form-label small fw-bold text-muted text-uppercase">Dari Tanggal</label>
                    <input type="date" name="dari" id="dari" class="form-control rounded-pill border-2 shadow-none" value="{{ request('dari') }}">
                </div>
                <div class="col-md-4">
                    <label for="sampai" class="form-label small fw-bold text-muted text-uppercase">Sampai Tanggal</label>
                    <input type="date" name="sampai" id="sampai" class="form-control rounded-pill border-2 shadow-none" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background: var(--gradient-primary); border: none;">
                        <i class="fa-solid fa-filter me-2"></i> Filter
                    </button>
                    @if(request('dari') || request('sampai'))
                    <a href="{{ route('pasien.rekam-medis') }}" class="btn btn-light rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-rotate-left me-2"></i> Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="col-12">
        <div class="card-custom bg-white border-0 shadow-sm overflow-hidden p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Tanggal & ID</th>
                            <th class="py-3">Keluhan & Diagnosa</th>
                            <th class="py-3">Tindakan</th>
                            <th class="py-3">Obat</th>
                            <th class="py-3">Dokter</th>
                            <th class="py-3 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $h)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($h->Tanggal)->format('d M Y') }}</div>
                                <small class="text-muted">RM-{{ $h->IdRekamMedis }}</small>
                            </td>
                            <td style="max-width: 250px;">
                                <div class="fw-bold small mb-1">{{ $h->Keluhan }}</div>
                                <div class="text-muted small italic">{{ Str::limit($h->Diagnosa, 80) }}</div>
                            </td>
                            <td>
                                @foreach($h->tindakan as $t)
                                <span class="badge bg-primary-soft text-primary rounded-pill mb-1 fw-semibold" style="font-size: 0.7rem;">
                                    {{ $t->NamaTindakan }}
                                </span>
                                @endforeach
                            </td>
                            <td>
                                @forelse($h->obat as $o)
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill mb-1 fw-semibold" style="font-size: 0.7rem;">
                                    {{ $o->NamaObat }}
                                </span>
                                @empty
                                <span class="text-muted small italic">N/A</span>
                                @endforelse
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ $h->dokter->Nama ?? 'Dr' }}&background=0ea5e9&color=fff" class="rounded-circle" width="30">
                                    <span class="small fw-semibold">{{ $h->dokter->Nama ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('pasien.rekam-medis.show', $h->IdRekamMedis) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" style="background: var(--gradient-primary); border: none;">
                                    Detail <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.7rem;"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted">
                                <i class="fa-solid fa-notes-medical fa-4x mb-4 opacity-25"></i>
                                <h4>Belum Ada Riwayat Medis</h4>
                                <p>Catatan medis Anda akan muncul secara otomatis setelah pemeriksaan selesai.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($histories->hasPages())
            <div class="p-4 border-top">
                {{ $histories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection