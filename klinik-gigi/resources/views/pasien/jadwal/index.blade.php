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
                            <th>No Antrian</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($b->jadwal->Tanggal)->format('d M Y') }}</div>
                                <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $b->jadwal->JamMulai }} - {{ $b->jadwal->JamSelesai }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ $b->jadwal->dokter->Nama ?? '-' }}&background=random" class="rounded-circle" width="30">
                                    <span>{{ $b->jadwal->dokter->Nama ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">{{ $b->NomorAntrian ?? '-' }}</div>
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
                                <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} rounded-pill px-3">
                                    {{ $b->Status }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                @if($b->Status == 'PRESENT')
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Batalkan</button>
                                @endif
                                <button class="btn btn-sm btn-light rounded-pill px-3">Detail</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted">
                                <i class="fa-solid fa-calendar-xmark fa-4x mb-4 opacity-25"></i>
                                <h4>Belum Ada Janji Temu</h4>
                                <p>Silakan buat janji temu untuk pemeriksaan rutin.</p>
                                <a href="{{ route('admin.booking.create') }}" class="btn btn-primary rounded-pill px-4">Buat Janji Temu</a>
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
