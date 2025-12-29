@extends('layouts.dashboard')

@section('title', 'Dashboard Dokter - Zenith Dental')
@section('theme', 'dokter')
@section('header-title', 'Area Dokter')
@section('header-subtitle', 'Selamat bertugas, ' . ($dokter->Nama ?? Auth::user()->name))

@section('sidebar-menu')
<a href="{{ route('dokter.dashboard') }}" class="nav-link active"><i class="fa-solid fa-stethoscope"></i> Praktek Hari Ini</a>
<a href="{{ route('dokter.jadwal') }}" class="nav-link"><i class="fa-solid fa-calendar-week"></i> Jadwal Saya</a>
<a href="{{ route('dokter.pasien') }}" class="nav-link"><i class="fa-solid fa-user-injured"></i> Data Pasien</a>
<a href="{{ route('dokter.riwayat') }}" class="nav-link"><i class="fa-solid fa-history"></i> Riwayat Praktek</a>
<a href="{{ route('dokter.notifications') }}" class="nav-link"><i class="fa-solid fa-bell"></i> Notifikasi</a>
@endsection

@section('content')

<div class="row g-4">
    <!-- Main Column: Schedule & Patient Queue -->
    <div class="col-lg-8">
        
        <!-- Today's Overview -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                 <div class="card-custom bg-info-subtle border-0 h-100">
                    <h6 class="text-info-emphasis fw-bold">Jadwal Hari Ini</h6>
                    @if($jadwalHariIni->isNotEmpty())
                        <h5 class="fw-bold mb-0 text-info-emphasis">{{ \Carbon\Carbon::today()->isoFormat('dddd, D MMM') }}</h5>
                        <h2 class="fw-bold mb-0 text-info-emphasis">{{ $jadwalHariIni->count() }} Sesi</h2>
                    @else
                        <h2 class="fw-bold mb-0 text-info-emphasis">Libur</h2>
                        <small>Tidak ada jadwal hari ini</small>
                    @endif
                 </div>
            </div>
            <div class="col-md-4">
                 <div class="card-custom bg-primary-subtle border-0 h-100">
                    <h6 class="text-primary-emphasis fw-bold">Total Pasien</h6>
                    <h2 class="fw-bold mb-0 text-primary-emphasis">{{ $antrian->count() }}</h2>
                    <small>{{ $totalPasienSelesai }} Selesai • {{ $totalPasienMenunggu }} Menunggu</small>
                 </div>
            </div>
            <div class="col-md-4">
                 <div class="card-custom bg-success-subtle border-0 h-100">
                    <h6 class="text-success-emphasis fw-bold">Status Ruangan</h6>
                    @if($jadwalHariIni->isNotEmpty())
                         <h2 class="fw-bold mb-0 text-success-emphasis">Ready</h2>
                         <small>Ruang Praktek Aktif</small>
                    @else
                         <h2 class="fw-bold mb-0 text-secondary">Closed</h2>
                         <small>-</small>
                    @endif
                 </div>
            </div>
        </div>

        <!-- Patient Queue -->
        <div class="card-custom mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-list-ol me-2"></i> Antrian Pasien</h5>
                <span class="badge bg-primary">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM') }}</span>
            </div>
            
            <div class="list-group list-group-flush rounded-3">
                @forelse($antrian as $index => $booking)
                    <!-- Active Patient (First in queue or based on status if we had 'IN_PROGRESS') -->
                    <div class="list-group-item p-3 mb-2 rounded border {{ $index == 0 && $booking->Status == 'PRESENT' ? 'border-primary bg-primary-subtle' : 'bg-white' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="{{ $index == 0 ? 'bg-primary text-white' : 'bg-light text-secondary' }} fs-5 fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $booking->pasien->Nama ?? 'Nama Pasien' }} <span class="badge bg-secondary ms-2 small" style="font-size: 0.6em;">{{ $booking->IdBooking }}</span></h6>
                                    <small class="text-muted d-block">
                                        <i class="fa-solid fa-clock me-1"></i> {{ $booking->formatted_tanggal_booking }}
                                        @if($booking->Status == 'COMPLETED')
                                            <span class="badge bg-success ms-1">Selesai</span>
                                        @elseif($booking->Status == 'CANCELLED')
                                            <span class="badge bg-danger ms-1">Batal</span>
                                        @else
                                            <span class="badge bg-info ms-1">Menunggu</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                             @if($booking->Status == 'PRESENT')
                                <a href="{{ route('dokter.rekam-medis.create', $booking->IdBooking) }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-user-md me-1"></i> Periksa
                                </a>
                             @else
                                <button class="btn btn-outline-secondary btn-sm" disabled>
                                    @if($booking->Status == 'COMPLETED') Selesai @else Info @endif
                                </button>
                             @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fa-solid fa-clipboard-list fa-3x text-muted mb-3 opacity-50"></i>
                        <h6 class="text-muted">Belum ada antrian pasien hari ini.</h6>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Right Column: Medical Record Input & Stats -->
    <div class="col-lg-4">
        
        <!-- Quick Note / Input RM -->
        <div class="card-custom mb-4 bg-white border-0 shadow-lg">
            <h6 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-pen-to-square"></i> Catatan Cepat</h6>
            
            @if($jadwalHariIni->isNotEmpty() && $antrian->count() > 0)
            <form>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih Pasien Aktif</label>
                    <select id="quickPasien" class="form-select">
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($antrian->where('Status', 'PRESENT') as $booking)
                            <option value="{{ $booking->IdBooking }}">{{ $booking->pasien->Nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-bold mb-0">Diagnosa Awal</label>
                        <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none" style="font-size: 0.8rem;" onclick="copyToClipboard(this)">
                            <i class="fa-regular fa-copy me-1"></i>Salin
                        </button>
                    </div>
                    <textarea id="quickDiagnosa" class="form-control" rows="3" placeholder="Tulis diagnosa sementara..."></textarea>
                </div>
                <button type="button" class="btn btn-primary w-100" onclick="goToInputRekamMedis()">
                    Input Rekam Medis Lengkap
                </button>
            </form>

            <script>
                function copyToClipboard(btn) {
                    const diagnosa = document.getElementById('quickDiagnosa');
                    if (!diagnosa.value) return;
                    
                    diagnosa.select();
                    navigator.clipboard.writeText(diagnosa.value);
                    
                    // Feedback visual
                    const originalContent = btn.innerHTML;
                    btn.innerHTML = '<i class="fa-solid fa-check me-1"></i>Tersalin';
                    setTimeout(() => { btn.innerHTML = originalContent; }, 2000);
                }

                function goToInputRekamMedis() {
                    const pasienSelect = document.getElementById('quickPasien');
                    const idBooking = pasienSelect.value;
                    
                    if (!idBooking) {
                        alert('Silakan pilih pasien terlebih dahulu untuk mengisi rekam medis.');
                        return;
                    }
                    
                    window.location.href = "{{ url('dokter/rekam-medis/create') }}/" + idBooking;
                }
            </script>
            @else
                <p class="text-muted small">Tidak ada pasien aktif untuk dibuatkan catatan saat ini.</p>
            @endif
        </div>

        <!-- Performance / Info -->
        <div class="card-custom">
            <h6 class="fw-bold mb-3">Informasi Dokter</h6>
             <div class="d-flex align-items-center mb-3">
                <div class="bg-light rounded-circle p-3 me-3">
                    <i class="fa-solid fa-user-doctor fa-2x text-primary"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">{{ $dokter->Nama ?? Auth::user()->name }}</h6>
                    <small class="text-muted">{{ ucfirst($dokter->Jabatan ?? 'Dokter') }}</small>
                </div>
            </div>
            <hr>
            <div class="row text-center">
                <div class="col-6 border-end">
                    <h5 class="fw-bold mb-0">{{ $antrian->count() }}</h5>
                    <small class="text-muted">Pasien Hari Ini</small>
                </div>
                <div class="col-6">
                    <h5 class="fw-bold mb-0">{{ $jadwalHariIni->sum('Kapasitas') }}</h5>
                    <small class="text-muted">Kapasitas</small>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
