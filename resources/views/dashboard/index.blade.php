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

        <!-- New: Obat Expiry Stats -->
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card-custom p-3 h-100 border-start border-4 border-danger">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Obat Expired</div>
                            <h2 class="mb-0 text-danger mt-2">{{ $data['obat_expired'] ?? 0 }}</h2>
                        </div>
                        <div class="display-4 text-danger opacity-25"><i class="fas fa-exclamation-triangle"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom p-3 h-100 border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Expiring Soon</div>
                            <h2 class="mb-0 text-warning mt-2">{{ $data['obat_expiring_soon'] ?? 0 }}</h2>
                        </div>
                        <div class="display-4 text-warning opacity-25"><i class="fas fa-clock"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom p-3 h-100 border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Kontrol Aktif</div>
                            <h2 class="mb-0 text-primary mt-2">{{ $data['active_tindakan_spesialis'] ?? 0 }}</h2>
                        </div>
                        <div class="display-4 text-primary opacity-25"><i class="fas fa-calendar-alt"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom p-3 h-100 border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Kontrol Selesai</div>
                            <h2 class="mb-0 text-success mt-2">{{ $data['completed_tindakan_spesialis'] ?? 0 }}</h2>
                        </div>
                        <div class="display-4 text-success opacity-25"><i class="fas fa-check-circle"></i></div>
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

        <!-- New: Kontrol Berkala Hari Ini -->
        <div class="row mb-4 g-3">
            <div class="col-md-12">
                <div class="card-custom p-3 border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Kontrol Berkala Hari Ini</div>
                            <h3 class="mb-0 text-warning mt-2">{{ $data['kontrol_berkala_hari_ini'] ?? 0 }} Sesi</h3>
                        </div>
                        <div class="display-4 text-warning opacity-25"><i class="fas fa-procedures"></i></div>
                    </div>
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

        <!-- New: Upcoming Sesi Kontrol Berkala -->
        <div class="card-custom mt-4">
            <div class="card-header-custom">Upcoming Kontrol Berkala (7 Hari)</div>
            <div class="p-3">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Sesi</th>
                            <th>Pasien</th>
                            <th>Tindakan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['upcoming_sesi'] ?? [] as $sesi)
                            <tr>
                                <td>#{{ $sesi->session_number }}</td>
                                <td>{{ $sesi->tindakanSpesialis->pasien->Nama }}</td>
                                <td>{{ $sesi->tindakanSpesialis->NamaTindakan }}</td>
                                <td>{{ \Carbon\Carbon::parse($sesi->scheduled_date)->format('d M Y') }}</td>
                                <td><span class="badge bg-primary">{{ $sesi->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Tidak ada sesi upcoming</td></tr>
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
                <!-- New: Next Kontrol Berkala -->
                <div class="card-custom mb-4">
                    <div class="card-header-custom bg-success text-white">Kontrol Berkala Berikutnya</div>
                    <div class="p-4 text-center">
                        @if($data['next_kontrol_berkala'] ?? false)
                            <h1 class="display-4 text-success">{{ \Carbon\Carbon::parse($data['next_kontrol_berkala']->scheduled_date)->format('d') }}</h1>
                            <h4 class="text-uppercase">{{ \Carbon\Carbon::parse($data['next_kontrol_berkala']->scheduled_date)->format('F Y') }}</h4>
                            <hr>
                            <h5>{{ $data['next_kontrol_berkala']->tindakanSpesialis->NamaTindakan }}</h5>
                            <p class="text-muted">Dokter: {{ $data['next_kontrol_berkala']->tindakanSpesialis->dokter->Nama }}</p>
                            <p class="small">Sesi ke-{{ $data['next_kontrol_berkala']->session_number }}</p>
                            <span class="badge bg-primary">{{ $data['next_kontrol_berkala']->status }}</span>
                        @else
                            <div class="py-4">
                                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                <p>Tidak ada kontrol berkala dijadwalkan.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Kontrol Berkala -->
        @if(count($data['riwayat_kontrol'] ?? []) > 0)
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card-custom">
                    <div class="card-header-custom">Riwayat Kontrol Berkala</div>
                    <div class="p-3">
                        <div class="row g-3">
                            @foreach($data['riwayat_kontrol'] as $kontrol)
                                <div class="col-md-4">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $kontrol->NamaTindakan }}</h6>
                                            <p class="card-text small text-muted">Dokter: {{ $kontter->dokter->Nama }}</p>
                                            <div class="progress mb-2" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $kontrol->getProgressPercentage() }}%" 
                                                     aria-valuenow="{{ $kontrol->getProgressPercentage() }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{ $kontrol->completed_sessions }}/{{ $kontrol->total_sessions }}
                                                </div>
                                            </div>
                                            <span class="badge 
                                                @if($kontrol->status == 'active') bg-primary
                                                @elseif($kontrol->status == 'completed') bg-success
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($kontrol->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif

</div>
@endsection