@extends('layouts.dashboard')

@section('title', 'Dashboard Pasien - Zenith Dental')
@section('theme', 'pasien')
@section('header-title', 'Halo, ' . (isset($pasien->Nama) ? $pasien->Nama : auth()->user()->name) . '!')
@section('header-subtitle', 'Senyum sehat Anda adalah prioritas kami.')

@section('sidebar-menu')
    <a href="{{ route('pasien.dashboard') }}" class="nav-link active">
        <i class="fa-solid fa-house"></i> Dashboard
    </a>
    <a href="{{ route('pasien.booking.create') }}" class="nav-link">
        <i class="fa-solid fa-calendar-plus"></i> Buat Janji Temu
    </a>
    <a href="{{ route('pasien.jadwal') }}" class="nav-link">
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
<div class="row g-4 mb-4">
    <!-- Quick Stats / Cards -->
    <div class="col-md-4">
        <div class="card-custom h-100 border-0 shadow-sm" style="background: var(--gradient-primary); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="opacity-75 mb-2">Janji Temu Mendatang</h6>
                    <h3 class="fw-bold mb-0">{{ $upcomingBookings->count() }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                    <i class="fa-solid fa-calendar-check fa-2x"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('pasien.booking.create') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold">
                    Buat Baru <i class="fa-solid fa-plus ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-custom h-100 bg-white border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Riwayat Pemeriksaan</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $medicalHistory->count() }}</h3>
                </div>
                <div class="bg-primary-soft p-3 rounded-circle text-primary">
                    <i class="fa-solid fa-stethoscope fa-2x"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-muted small"><i class="fa-solid fa-clock-rotate-left me-1"></i> Terakhir: {{ $medicalHistory->first()->Tanggal ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-custom h-100 bg-white border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Poin Senyum</h6>
                    <h3 class="fw-bold mb-0 text-dark">1.250</h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                    <i class="fa-solid fa-star fa-2x"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-muted small">Tukarkan dengan diskon layanan!</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Upcoming Bookings & Medical History -->
    <div class="col-lg-8">
        <!-- Upcoming Bookings -->
        <div class="card-custom bg-white border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-calendar-day text-primary me-2"></i>Janji Temu Mendatang</h5>
                <a href="{{ route('pasien.jadwal') }}" class="text-primary text-decoration-none small fw-bold">Lihat Semua</a>
            </div>
            
            @forelse($upcomingBookings as $booking)
            <div class="d-flex align-items-center p-3 rounded-4 mb-3 border border-light-subtle hover-shadow transition-all">
                <div class="bg-primary-soft text-primary p-3 rounded-4 text-center me-3" style="min-width: 70px;">
                    <div class="fw-bold h5 mb-0">{{ \Carbon\Carbon::parse($booking->jadwal->Tanggal)->format('d') }}</div>
                    <div class="small fw-semibold text-uppercase">{{ \Carbon\Carbon::parse($booking->jadwal->Tanggal)->format('M') }}</div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">{{ $booking->jadwal->dokter->Nama ?? 'Dokter' }}</h6>
                    <p class="text-muted small mb-0">
                        <i class="fa-solid fa-clock me-1"></i> {{ \Carbon\Carbon::parse($booking->jadwal->JamMulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jadwal->JamSelesai)->format('H:i') }}
                    </p>
                </div>
                <div class="ms-auto text-end">
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 mb-2">Terkonfirmasi</span>
                    <div>
                        <form action="{{ route('pasien.booking.cancel', $booking->IdBooking) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold shadow-sm" style="font-size: 0.75rem;" onclick="return confirm('Apakah Anda yakin ingin membatalkan janji ini?')">
                                <i class="fa-solid fa-xmark me-1"></i> Batal
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <img src="https://img.icons8.com/bubbles/100/000000/calendar.png" class="mb-3 opacity-50">
                <p class="text-muted">Belum ada janji temu aktif.</p>
                <a href="{{ route('pasien.booking.create') }}" class="btn btn-primary rounded-pill px-4">Buat Janji Sekarang</a>
            </div>
            @endforelse
        </div>

        <!-- Recent Medical History -->
        <div class="card-custom bg-white border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-history text-primary me-2"></i>Riwayat Pemeriksaan Terakhir</h5>
                <a href="{{ route('pasien.rekam-medis') }}" class="text-primary text-decoration-none small fw-bold">Semua Riwayat</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light border-0">
                        <tr>
                            <th class="border-0 rounded-start-4 ps-3">Tanggal</th>
                            <th class="border-0">Tindakan</th>
                            <th class="border-0">Dokter</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 rounded-end-4 text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicalHistory as $history)
                        <tr>
                            <td class="ps-3">{{ \Carbon\Carbon::parse($history->Tanggal)->format('d M Y') }}</td>
                            <td>
                                @foreach($history->tindakan as $tindakan)
                                    <span class="badge bg-primary-soft text-primary rounded-pill mb-1" style="font-size: 0.7rem;">{{ $tindakan->NamaTindakan }}</span>
                                @endforeach
                            </td>
                            <td class="small">{{ $history->dokter->Nama ?? '-' }}</td>
                            <td><span class="badge bg-success-subtle text-success rounded-pill px-2">Selesai</span></td>
                            <td class="text-end pe-3">
                                <a href="{{ route('pasien.rekam-medis.show', $history->IdRekamMedis) }}" class="btn btn-sm btn-link text-primary p-0 fw-bold text-decoration-none small">
                                    Detail <i class="fa-solid fa-chevron-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted small">Belum ada riwayat pemeriksaan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Broadcasts & Promotions -->
    <div class="col-lg-4">
        <!-- Announcements / Broadcasts -->
        <div class="card-custom bg-white border-0 shadow-sm mb-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-bullhorn text-primary me-2"></i>Pengumuman</h5>
            @forelse($broadcasts as $broadcast)
            <div class="p-3 rounded-4 bg-light border-start border-primary border-4 mb-3">
                <h6 class="fw-bold mb-1">{{ $broadcast->Title }}</h6>
                <p class="text-muted small mb-2">{{ Str::limit($broadcast->Content, 100) }}</p>
                <small class="text-primary fw-semibold">{{ $broadcast->created_at->diffForHumans() }}</small>
            </div>
            @empty
            <p class="text-center text-muted py-3 small">Tidak ada pengumuman saat ini.</p>
            @endforelse
        </div>

        <!-- Promotions / Tips -->
        <div class="card-custom border-0 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
            <div class="text-center py-3">
                <i class="fa-solid fa-lightbulb fa-3x text-warning mb-3"></i>
                <h5 class="fw-bold mb-2">Tips Kesehatan Gigi</h5>
                <p class="small opacity-75 mb-4">Jangan lupa sikat gigi minimal 2 kali sehari dan gunakan dental floss untuk hasil maksimal!</p>
                <div class="bg-white bg-opacity-10 p-3 rounded-4">
                    <p class="small mb-0 italic">"Gigi yang bersih adalah kunci dari tubuh yang sehat."</p>
                </div>
            </div>
        </div>
        
        <!-- Promotion Banner -->
        <div class="mt-4 rounded-4 overflow-hidden position-relative shadow-sm" style="height: 200px;">
            <img src="https://images.unsplash.com/photo-1593054941324-f725a3d7e799?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-100 h-100 object-fit-cover">
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-40 d-flex align-items-end p-4">
                <div>
                    <span class="badge bg-accent text-white mb-2">Promo</span>
                    <h6 class="text-white fw-bold mb-0">Diskon 50% Scaling Gigi</h6>
                    <small class="text-white opacity-75">Hanya sampai akhir bulan ini!</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .hover-shadow:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endsection
