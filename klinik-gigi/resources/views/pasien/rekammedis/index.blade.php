@extends('layouts.dashboard')

@section('title', 'Rekam Medis - Zenith Dental')
@section('header-title', 'Riwayat Perawatan Gigi')

@section('sidebar-menu')
<a href="{{ route('pasien.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Beranda</a>
<a href="{{ route('pasien.jadwal') }}" class="nav-link"><i class="fa-solid fa-calendar-check"></i> Jadwal Saya</a>
<a href="{{ route('pasien.rekam-medis') }}" class="nav-link active"><i class="fa-solid fa-file-medical"></i> Rekam Medis</a>
<a href="{{ route('pasien.notifications') }}" class="nav-link"><i class="fa-solid fa-bell"></i> Notifikasi</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Dokter</th>
                            <th>Diagnosis/Keluhan</th>
                            <th>Tindakan</th>
                            <th>Obat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $h)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($h->Tanggal)->format('d M Y') }}</div>
                                <small class="text-muted">{{ $h->IdRekamMedis }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ $h->dokter->Nama ?? '-' }}&background=random" class="rounded-circle" width="30">
                                    <span>{{ $h->dokter->Nama ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold small">{{ $h->Keluhan }}</div>
                                <div class="text-muted small">Diag: {{ $h->Diagnosa }}</div>
                            </td>
                            <td>
                                @foreach($h->tindakan as $t)
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill mb-1">{{ $t->NamaTindakan }}</span>
                                @endforeach
                            </td>
                            <td>
                                @foreach($h->obat as $o)
                                    <div class="small text-dark">• {{ $o->NamaObat }}</div>
                                @endforeach
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted">
                                <i class="fa-solid fa-notes-medical fa-4x mb-4 opacity-25"></i>
                                <h4>Belum Ada Riwayat Medis</h4>
                                <p>Catatan medis Anda akan muncul setelah kunjungan pemeriksaan.</p>
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
