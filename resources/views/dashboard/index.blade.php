@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-secondary fw-bold">Dashboard</h2>
        <div class="text-muted small">{{ now()->format('l, d F Y') }}</div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card-custom p-3 h-100 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Total Pasien</div>
                        <h2 class="mb-0 text-primary mt-2">{{ $stats['total_pasien'] }}</h2>
                    </div>
                    <div class="display-4 text-primary opacity-25">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom p-3 h-100 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Total Dokter</div>
                        <h2 class="mb-0 text-success mt-2">{{ $stats['total_dokter'] }}</h2>
                    </div>
                    <div class="display-4 text-success opacity-25">
                        <i class="fas fa-user-md"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom p-3 h-100 border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Booking Hari Ini</div>
                        <h2 class="mb-0 text-info mt-2">{{ $stats['booking_hari_ini'] }}</h2>
                    </div>
                    <div class="display-4 text-info opacity-25">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Booking Hari Ini -->
    <div class="card-custom">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list-alt me-2 text-primary"></i> Booking Hari Ini</span>
            <a href="{{ route('booking.index') }}" class="btn btn-sm btn-primary rounded-pill px-3">Lihat Semua</a>
        </div>
        <div class="p-3">
            @if($bookingsToday->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>
                    <p>Tidak ada booking untuk hari ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 ps-3 rounded-start">Jam</th>
                                <th class="py-3">Pasien</th>
                                <th class="py-3">Dokter</th>
                                <th class="py-3 pe-3 rounded-end text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookingsToday as $booking)
                            <tr>
                                <td class="ps-3 fw-bold text-primary">{{ $booking->JamMulai }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded-circle me-2" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user small text-muted"></i>
                                        </div>
                                        {{ $booking->nama_pasien }}
                                    </div>
                                </td>
                                <td>{{ $booking->nama_dokter }}</td>
                                <td class="text-end pe-3">
                                    @if($booking->Status == 'PRESENT')
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Hadir</span>
                                    @elseif($booking->Status == 'CANCELLED')
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Batal</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">{{ $booking->Status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection