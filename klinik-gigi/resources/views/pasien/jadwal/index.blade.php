@extends('layouts.dashboard')

@section('title', 'Jadwal Saya - Zenith Dental')
@section('header-title', 'Daftar Janji Temu')

@section('sidebar-menu')
<a href="{{ route('pasien.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Beranda</a>
<a href="{{ route('pasien.jadwal') }}" class="nav-link active"><i class="fa-solid fa-calendar-check"></i> Jadwal Saya</a>
<a href="{{ route('pasien.rekam-medis') }}" class="nav-link"><i class="fa-solid fa-file-medical"></i> Rekam Medis</a>
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
                            <th class="ps-4">Tanggal & Waktu</th>
                            <th>Dokter</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $b->jadwal->Tanggal ? $b->jadwal->Tanggal->format('d M Y') : '-' }}</div>
                                <small class="text-muted">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    {{ $b->jadwal->JamMulai ? $b->jadwal->JamMulai->format('H:i') : '-' }} - 
                                    {{ $b->jadwal->JamAkhir ? $b->jadwal->JamAkhir->format('H:i') : '-' }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ $b->jadwal->dokter->Nama ?? '-' }}&background=random" class="rounded-circle" width="30">
                                    <span>{{ $b->jadwal->dokter->Nama ?? '-' }}</span>
                                </div>
                            </td>

                            <td>
                                @php
                                    $statusColor = [
                                        'PRESENT' => 'info',
                                        'COMPLETED' => 'success',
                                        'CANCELLED' => 'danger',
                                        'WAITING' => 'warning'
                                    ][$b->Status] ?? 'secondary';
                                @endphp
                                <div class="d-flex flex-column align-items-start gap-1">
                                    <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} rounded-pill px-3">
                                        {{ $b->Status }}
                                    </span>
                                    @if($b->Status == 'CANCELLED' && $b->CancelledAt)
                                        <small class="text-danger" style="font-size: 0.7rem;">
                                            Dibatalkan: {{ $b->CancelledAt->format('d M Y, H:i') }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                @if($b->Status == 'PRESENT')
                                <form action="{{ route('pasien.booking.cancel', $b->IdBooking) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan janji temu ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Batalkan</button>
                                </form>
                                @elseif($b->Status == 'COMPLETED')
                                <a href="{{ route('pasien.rekam-medis', ['dari' => $b->jadwal->Tanggal ? $b->jadwal->Tanggal->format('Y-m-d') : '', 'sampai' => $b->jadwal->Tanggal ? $b->jadwal->Tanggal->format('Y-m-d') : '']) }}" class="btn btn-sm btn-primary rounded-pill px-4">
                                    <i class="fa-solid fa-file-medical me-1"></i> Detail
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center text-muted">
                                <i class="fa-solid fa-calendar-xmark fa-4x mb-4 opacity-25"></i>
                                <h4>Belum Ada Janji Temu</h4>
                                <p>Silakan buat janji temu untuk pemeriksaan rutin.</p>
                                <a href="{{ route('pasien.booking.create') }}" class="btn btn-primary rounded-pill px-4">Buat Janji Temu</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
            <div class="p-4 border-top">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
