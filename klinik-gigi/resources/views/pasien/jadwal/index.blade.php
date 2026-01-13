@extends('layouts.dashboard')

@section('title', 'Jadwal Saya - Zenith Dental')
@section('no-sidebar', 'true')
@section('header-title', 'Daftar Janji Temu')

@section('navbar-menu')
<a href="{{ route('pasien.dashboard') }}" class="nav-link {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}">Beranda</a>
<a href="{{ route('pasien.jadwal') }}" class="nav-link {{ request()->routeIs('pasien.jadwal') ? 'active' : '' }}">Jadwal Saya</a>
<a href="{{ route('pasien.rekam-medis') }}" class="nav-link {{ request()->routeIs('pasien.rekam-medis') ? 'active' : '' }}">Rekam Medis</a>
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
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="confirmCancel('{{ route('pasien.booking.cancel', $b->IdBooking) }}', '{{ $b->jadwal->dokter->Nama }}', '{{ $b->jadwal->Tanggal->format('d M Y') }}')">
                                    Batalkan
                                </button>
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
@push('scripts')
<script>
    function confirmCancel(url, dokter, tanggal) {
        Swal.fire({
            title: 'Batalkan Janji Temu?',
            html: `Anda akan membatalkan janji temu dengan <br><span class="text-primary fw-bold">${dokter}</span><br>pada <span class="text-dark fw-bold">${tanggal}</span>.`,
            icon: 'warning',
            iconColor: '#f87171',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fa-solid fa-trash-can me-2"></i>Ya, Batalkan',
            cancelButtonText: 'Kembali',
            reverseButtons: true,
            showClass: {
                popup: 'animate__animated animate__fadeInUp animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown animate__faster'
            },
            customClass: {
                popup: 'rounded-5 shadow-lg border-0 p-4',
                confirmButton: 'rounded-pill px-4 py-2 fw-bold shadow-sm',
                cancelButton: 'rounded-pill px-4 py-2 fw-bold'
            },
            backdrop: `
                rgba(15, 23, 42, 0.4)
                left top
                no-repeat
            `
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection
