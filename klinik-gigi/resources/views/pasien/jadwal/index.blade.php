@extends('layouts.dashboard')

@section('theme', 'pasien')
@section('title', 'Jadwal Saya - Zenith Dental')
@section('header-title', 'Jadwal & Riwayat Janji')
@section('header-subtitle', 'Pantau semua jadwal pemeriksaan Anda di sini.')

@section('sidebar-menu')
    <a href="{{ route('pasien.dashboard') }}" class="nav-link">
        <i class="fa-solid fa-house"></i> Dashboard
    </a>
    <a href="{{ route('pasien.booking.create') }}" class="nav-link">
        <i class="fa-solid fa-calendar-plus"></i> Buat Janji Temu
    </a>
    <a href="{{ route('pasien.jadwal') }}" class="nav-link active">
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
<div class="row">
    <div class="col-12">
        <div class="card-custom bg-white border-0 shadow-sm overflow-hidden p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">ID Booking</th>
                            <th class="py-3">Tanggal & Sesi</th>
                            <th class="py-3">Dokter</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#{{ $booking->IdBooking }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-soft text-primary p-2 rounded-3 me-3 text-center" style="min-width: 50px;">
                                        <div class="fw-bold small">{{ \Carbon\Carbon::parse($booking->jadwal->Tanggal)->format('d') }}</div>
                                        <div class="small fw-bold" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($booking->jadwal->Tanggal)->format('M') }}</div>
                                    </div>
                                    <div>
                                        <div class="fw-bold small">{{ \Carbon\Carbon::parse($booking->jadwal->Tanggal)->format('l, d F Y') }}</div>
                                        <small class="text-muted">{{ $booking->jadwal->sesi }} ({{ \Carbon\Carbon::parse($booking->jadwal->JamMulai)->format('H:i') }})</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ $booking->jadwal->dokter->Nama ?? 'Dokter' }}&background=random" class="rounded-circle" width="30">
                                    <span class="small fw-semibold">{{ $booking->jadwal->dokter->Nama ?? 'Dokter' }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $statusClass = [
                                        'PRESENT' => 'bg-success-subtle text-success',
                                        'CANCELLED' => 'bg-danger-subtle text-danger',
                                        'COMPLETED' => 'bg-primary-subtle text-primary',
                                        'EXPIRED' => 'bg-secondary-subtle text-secondary'
                                    ][$booking->Status] ?? 'bg-light text-dark';
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-3 py-2 small fw-bold">
                                    {{ $booking->Status }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                @if($booking->Status == 'PRESENT')
                                <form action="{{ route('pasien.booking.cancel', $booking->IdBooking) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold shadow-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan janji ini?')">
                                        <i class="fa-solid fa-xmark me-1"></i> Batal
                                    </button>
                                </form>
                                @elseif($booking->Status == 'COMPLETED' && $booking->rekamMedis)
                                <a href="{{ route('pasien.rekam-medis.show', $booking->rekamMedis->IdRekamMedis) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" style="background: var(--gradient-primary); border: none;">
                                    <i class="fa-solid fa-file-prescription me-1"></i> Rekam Medis
                                </a>
                                @else
                                <span class="text-muted small italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted">
                                <i class="fa-solid fa-calendar-xmark fa-4x mb-4 opacity-25"></i>
                                <h4>Belum Ada Jadwal</h4>
                                <p>Silahkan buat janji temu pertama Anda!</p>
                                <a href="{{ route('pasien.booking.create') }}" class="btn btn-primary rounded-pill px-4" style="background: var(--gradient-primary); border: none;">Buat Janji Janji Temu</a>
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
