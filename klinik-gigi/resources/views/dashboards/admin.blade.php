@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - Zenith Dental')
@section('theme', 'admin')
@section('header-title', 'Dashboard Admin')
@section('header-subtitle', 'Kelola operasional klinik secara efisien')

@section('sidebar-menu')
<a href="{{ route('admin.dashboard') }}" class="nav-link active"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('admin.booking') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.pasien') }}" class="nav-link"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
<a href="{{ route('admin.obat') }}" class="nav-link"><i class="fa-solid fa-pills"></i> Data Obat</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
<a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('styles')
<style>
    .card-dashboard {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        transition: transform 0.3s ease;
    }
    .card-dashboard:hover { transform: translateY(-5px); }
    .table-compact td { padding: 12px 8px; font-size: 0.9rem; border-color: #f1f1f1; }
    .table-compact th { background: #f8f9fa; border: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-bar { height: 6px; border-radius: 10px; }
    .audit-item { border-left: 3px solid transparent; padding-left: 15px; margin-bottom: 20px; position: relative; }
    .audit-item::before { content: ''; position: absolute; left: -6px; top: 0; width: 10px; height: 10px; border-radius: 50%; background: #dee2e6; }
    .audit-item.active::before { background: var(--primary); }
    .scroll-custom::-webkit-scrollbar { width: 4px; }
    .scroll-custom::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }
</style>
@endsection

@section('content')

<!-- Quick Stats Area -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-dashboard p-3 d-flex align-items-center gap-3 border-start border-primary border-4">
            <div class="bg-primary-subtle text-primary p-2 rounded-3"><i class="fa-solid fa-users"></i></div>
            <div><small class="text-muted d-block">Total Pasien</small><h5 class="fw-bold mb-0">{{ $totalPasien }}</h5></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard p-3 d-flex align-items-center gap-3 border-start border-success border-4">
            <div class="bg-success-subtle text-success p-2 rounded-3"><i class="fa-solid fa-calendar-check"></i></div>
            <div><small class="text-muted d-block">Booking Hari Ini</small><h5 class="fw-bold mb-0">{{ $totalBookingToday }}</h5></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard p-3 d-flex align-items-center gap-3 border-start border-warning border-4">
            <div class="bg-warning-subtle text-warning p-2 rounded-3"><i class="fa-solid fa-wallet"></i></div>
            <div><small class="text-muted d-block">Omzet (Bulan)</small><h5 class="fw-bold mb-0 text-truncate" style="max-width: 130px;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h5></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-dashboard p-3 d-flex align-items-center gap-3 border-start border-info border-4">
            <div class="bg-info-subtle text-info p-2 rounded-3"><i class="fa-solid fa-stethoscope"></i></div>
            <div><small class="text-muted d-block">Tim Medis</small><h5 class="fw-bold mb-0">{{ $totalDokter }}</h5></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Booking Table -->
    <div class="col-lg-8">
        <div class="card-dashboard p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold m-0"><i class="fa-solid fa-list-check me-2 text-primary"></i>Booking Antrian Terbaru</h6>
                <a href="{{ route('admin.booking') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua <i class="fa-solid fa-arrow-right small"></i></a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-compact">
                    <thead>
                        <tr>
                            <th>Pasien</th>
                            <th>Dokter & Layanan</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $booking->pasien->Nama ?? '-' }}</div>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ $booking->pasien->NoTelp ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $booking->jadwal->dokter->Nama ?? '-' }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">{{ $booking->jadwal->Tanggal }} | {{ $booking->jadwal->sesi ?? '-' }}</div>
                            </td>
                            <td>
                                @php
                                    $badgeClass = 'secondary';
                                    if($booking->Status == 'PRESENT') $badgeClass = 'success';
                                    elseif($booking->Status == 'COMPLETED') $badgeClass = 'primary';
                                    elseif($booking->Status == 'CANCELLED') $badgeClass = 'danger';
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}-subtle text-{{ $badgeClass }} border-0 px-2 rounded-pill" style="font-size: 0.7rem;">
                                    {{ $booking->Status }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if($booking->Status == 'PRESENT')
                                <form action="{{ route('admin.booking.destroy', $booking->IdBooking) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-3 rounded-pill" style="font-size: 0.75rem;">Cancel</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted small">Belum ada antrian aktif saat ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Promotion Blast (Integrated) -->
            <div class="mt-4 pt-4 border-top">
                <label class="small fw-bold text-muted mb-2"><i class="fa-solid fa-paper-plane me-1"></i> Quick Broadcast Pengumuman</label>
                <div class="input-group">
                    <input type="text" class="form-control border-light-subtle bg-light small" placeholder="Ketik pesan untuk semua pasien...">
                    <button class="btn btn-dark px-4 small">Kirim</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Stats & Audit -->
    <div class="col-lg-4">
        <!-- Service Stats -->
        <div class="card-dashboard p-4 mb-4">
             <h6 class="fw-bold mb-4"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Tren Layanan Teratas</h6>
             @php $colors = ['primary', 'info', 'warning']; @endphp
             @forelse($topTindakan as $index => $t)
             <div class="mb-4">
                 <div class="d-flex justify-content-between small mb-1">
                     <span class="fw-semibold">{{ $t->NamaTindakan }}</span>
                     <span class="text-muted">{{ round(($t->count / max(1, $totalPemeriksaan)) * 100, 1) }}%</span>
                 </div>
                 <div class="progress shadow-none stat-bar bg-light">
                     <div class="progress-bar bg-{{ $colors[$index] ?? 'primary' }}" style="width: {{ ($t->count / max(1, $totalPemeriksaan)) * 100 }}%"></div>
                 </div>
             </div>
             @empty
             <p class="text-muted small">Data belum tersedia.</p>
             @endforelse
        </div>

        <!-- Audit Activities -->
        <div class="card-dashboard p-4">
            <h6 class="fw-bold mb-4"><i class="fa-solid fa-fingerprint me-2 text-primary"></i>Aktivitas Audit</h6>
            <div class="scroll-custom" style="max-height: 280px; overflow-y: auto;">
                @forelse($auditActivities as $audit)
                <div class="audit-item {{ $loop->first ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="fa-solid {{ $audit['icon'] }} text-{{ $audit['color'] }} small" style="font-size: 0.7rem;"></i>
                        <span class="fw-bold small">{{ $audit['title'] }}</span>
                    </div>
                    <div class="text-muted" style="font-size: 0.75rem; line-height: 1.4;">{{ $audit['desc'] }}</div>
                    <small class="text-primary-emphasis opacity-75" style="font-size: 0.65rem;">{{ $audit['time'] }}</small>
                </div>
                @empty
                <p class="text-muted small">Tidak ada aktivitas audit.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
