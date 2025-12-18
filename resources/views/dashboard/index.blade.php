@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-secondary fw-bold">Dashboard</h2>
            <div class="text-muted small">Halo, {{ auth()->user()->name }}! ({{ ucfirst(auth()->user()->role) }})</div>
        </div>
        <div class="text-muted small">{{ now()->format('l, d F Y') }}</div>
    </div>
    
    @if(auth()->user()->role === 'admin')
        <!-- Admin Dashboard -->
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card-custom p-3 h-100 border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Total Pasien</div>
                            <h2 class="mb-0 text-primary mt-2">{{ $data['total_pasien'] ?? 0 }}</h2>
                        </div>
                        <div class="display-4 text-primary opacity-25"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom p-3 h-100 border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Total Dokter</div>
                            <h2 class="mb-0 text-success mt-2">{{ $data['total_dokter'] ?? 0 }}</h2>
                        </div>
                        <div class="display-4 text-success opacity-25"><i class="fas fa-user-md"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom p-3 h-100 border-start border-4 border-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Booking Hari Ini</div>
                            <h2 class="mb-0 text-info mt-2">{{ $data['booking_hari_ini'] ?? 0 }}</h2>
                        </div>
                        <div class="display-4 text-info opacity-25"><i class="fas fa-calendar-check"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Bookings for Admin -->
        <div class="card-custom">
            <div class="card-header-custom">Booking Terakhir</div>
            <div class="p-3">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['latest_bookings'] ?? [] as $booking)
                            <tr>
                                <td>{{ $booking->pasien->user->name ?? '-' }}</td>
                                <td>{{ $booking->jadwal->dokter->nama ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->tgl_booking)->format('d M Y') }}</td>
                                <td><span class="badge bg-info">{{ $booking->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Belum ada booking</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @elseif(auth()->user()->role === 'dokter')
        <!-- Dokter Dashboard -->
        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="card-custom p-3 h-100 border-start border-4 border-primary">
                    <h5 class="text-muted small text-uppercase fw-bold">Jadwal Hari Ini</h5>
                    @if($data['jadwal_hari_ini'] ?? false)
                        <h3 class="text-primary mt-2">{{ $data['jadwal_hari_ini']->jam_mulai }} - {{ $data['jadwal_hari_ini']->jam_selesai }}</h3>
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <h4 class="text-muted mt-2">Tidak ada jadwal</h4>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom p-3 h-100 border-start border-4 border-info">
                    <h5 class="text-muted small text-uppercase fw-bold">Pasien Hari Ini</h5>
                    <h2 class="text-info mt-2">{{ $data['pasien_hari_ini'] ?? 0 }}</h2>
                    <div class="small text-muted">Orang</div>
                </div>
            </div>
        </div>

        <div class="card-custom">
            <div class="card-header-custom">Daftar Pasien Menunggu</div>
            <div class="p-3">
                 <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No Antrian</th>
                            <th>Nama Pasien</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['appointments'] ?? [] as $apt)
                            <tr>
                                <td>{{ $apt->no_antrian }}</td>
                                <td>{{ $apt->pasien->user->name ?? '-' }}</td>
                                <td>{{ $apt->keluhan }}</td>
                                <td>{{ $apt->status }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary">Periksa</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Tidak ada pasien hari ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @elseif(auth()->user()->role === 'pasien')
        <!-- Pasien Dashboard -->
        <div class="row">
            <div class="col-md-6">
                <div class="card-custom mb-4">
                    <div class="card-header-custom bg-primary text-white">Jadwal Pemeriksaan Berikutnya</div>
                    <div class="p-4 text-center">
                        @if($data['next_appointment'] ?? false)
                            <h1 class="display-4 text-primary">{{ \Carbon\Carbon::parse($data['next_appointment']->tgl_booking)->format('d') }}</h1>
                            <h4 class="text-uppercase">{{ \Carbon\Carbon::parse($data['next_appointment']->tgl_booking)->format('F Y') }}</h4>
                            <hr>
                            <h5>{{ $data['next_appointment']->jadwal->dokter->nama ?? 'Dokter' }}</h5>
                            <p class="text-muted">{{ $data['next_appointment']->jadwal->jam_mulai }} WIB</p>
                            <span class="badge bg-warning text-dark">{{ $data['next_appointment']->status }}</span>
                        @else
                            <div class="py-4">
                                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                <p>Tidak ada jadwal pemeriksaan.</p>
                                <a href="{{ route('booking.index') }}" class="btn btn-primary rounded-pill">Buat Janji Baru</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom">
                    <div class="card-header-custom">Riwayat Pemeriksaan</div>
                    <div class="p-3">
                        <ul class="list-group list-group-flush">
                            @forelse($data['riwayat'] ?? [] as $hist)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($hist->tgl_booking)->format('d M Y') }}</div>
                                        <div class="small text-muted">{{ $hist->jadwal->dokter->nama ?? '-' }}</div>
                                    </div>
                                    <span class="badge bg-secondary">{{ $hist->status }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">Belum ada riwayat</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection