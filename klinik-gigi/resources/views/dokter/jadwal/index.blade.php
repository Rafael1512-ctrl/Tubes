@extends('layouts.dashboard')

@section('theme','dokter')
@section('title','Jadwal Saya')
@section('header-title','Jadwal Praktek Saya')
@section('header-subtitle','Kelola dan lihat jadwal praktek mendatang')

@section('sidebar-menu')
<a href="/dokter/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('dokter.jadwal') }}" class="nav-link active"><i class="fa-solid fa-calendar-week"></i> Jadwal Saya</a>
<a href="{{ route('dokter.pasien') }}" class="nav-link"><i class="fa-solid fa-user-injured"></i> Data Pasien</a>
<a href="{{ route('dokter.riwayat') }}" class="nav-link"><i class="fa-solid fa-history"></i> Riwayat Praktek</a>
@endsection

@section('styles')
<style>
    .schedule-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 5px solid transparent;
    }
    .schedule-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08) !important;
    }
    .schedule-card.today {
        border-left-color: #0d6efd;
        background-color: #f8f9fa;
    }
    .date-box {
        width: 60px;
        height: 60px;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-8">
        <div class="card-custom mb-3">
            <h5 class="fw-bold mb-3"><i class="fa-regular fa-calendar-check me-2"></i>Jadwal Mendatang</h5>
            
            <div class="d-flex flex-column gap-3">
                @forelse($jadwals as $jadwal)
                @php
                    $isToday = $jadwal->Tanggal->isToday();
                @endphp
                <div class="card shadow-sm border-0 schedule-card {{ $isToday ? 'today' : '' }}">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <!-- Date Box -->
                            <div class="date-box px-4 py-2 rounded-3 d-flex flex-column align-items-center justify-content-center me-3 text-white {{ $isToday ? 'bg-primary' : 'bg-secondary' }}">
                                <span class="small text-uppercase fw-bold">{{ $jadwal->Tanggal->isoFormat('MMM') }}</span>
                                <span class="fs-4 fw-bold">{{ $jadwal->Tanggal->format('d') }}</span>
                            </div>
                            
                            <!-- Info -->
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-1">{{ $jadwal->Tanggal->isoFormat('dddd') }} <span class="text-muted fw-normal ms-1">({{ $jadwal->sesi }})</span></h6>
                                    <div class="d-flex gap-1">
                                        @if($isToday)
                                            <span class="badge bg-primary">HARI INI</span>
                                        @endif
                                        <span class="badge bg-{{ $jadwal->Status == 'Available' ? 'success' : ($jadwal->Status == 'Full' ? 'warning' : 'danger') }}">
                                            {{ strtoupper($jadwal->Status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-muted small mb-2">
                                    <i class="fa-regular fa-clock me-2"></i> {{ $jadwal->formatted_jam }}
                                </div>
                                
                                <!-- Progress Bar Kapasitas -->
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        @php $percent = ($jadwal->bookings_count / $jadwal->Kapasitas) * 100; @endphp
                                        <div class="progress-bar {{ $percent >= 100 ? 'bg-danger' : 'bg-success' }}" role="progressbar" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="small fw-bold {{ $percent >= 100 ? 'text-danger' : 'text-success' }}">
                                        {{ $jadwal->bookings_count }} / {{ $jadwal->Kapasitas }} Pasien
                                    </span>
                                </div>
                            </div>

                            <!-- Action -->
                             <!-- Only show 'Lihat' if today or past, logic can vary -->
                             {{-- <button class="btn btn-light btn-sm ms-3 text-muted"><i class="fa-solid fa-chevron-right"></i></button> --}}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" alt="Empty" style="width: 150px; opacity: 0.5;">
                    <p class="text-muted mt-3">Belum ada jadwal praktek mendatang.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $jadwals->links() }}
            </div>
        </div>
    </div>

    <!-- Right Side: Summary -->
    <div class="col-lg-4">
        <div class="card-custom bg-white border-0 shadow-sm mb-4">
            <h6 class="fw-bold mb-3 text-secondary text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Statistik Bulan Ini</h6>
            <div class="row text-center g-2">
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3">
                        <h4 class="fw-bold text-primary mb-0">{{ $jadwals->count() }}</h4>
                        <small class="text-muted" style="font-size: 0.75rem;">Total Sesi</small>
                    </div>
                </div>
                <div class="col-6">
                     <div class="p-3 bg-light rounded-3">
                        <h4 class="fw-bold text-success mb-0">{{ $jadwals->sum('bookings_count') }}</h4>
                        <small class="text-muted" style="font-size: 0.75rem;">Total Pasien</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-custom bg-primary text-white border-0">
            <div class="d-flex align-items-center mb-3">
                <i class="fa-solid fa-circle-info me-2 fs-4"></i>
                <h6 class="fw-bold mb-0">Info Penting</h6>
            </div>
            <p class="small opacity-75 mb-0">
                Jika Anda berhalangan hadir pada jadwal yang telah ditentukan, mohon segera hubungi Admin paling lambat H-1 untuk penyesuaian jadwal dan pemberitahuan kepada pasien.
            </p>
        </div>
    </div>
</div>

@endsection
