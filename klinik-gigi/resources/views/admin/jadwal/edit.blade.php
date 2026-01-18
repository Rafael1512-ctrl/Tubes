@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Edit Jadwal Dokter')
@section('header-title','Edit Jadwal Dokter')
@section('header-subtitle','Ubah status dan kapasitas jadwal')

@section('sidebar-menu')
 <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('admin.jadwal') }}" class="nav-link {{ request()->routeIs(['admin.jadwal*', 'admin.booking*']) ? 'active' : '' }}"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.pasien') }}" class="nav-link {{ request()->routeIs('admin.pasien*') ? 'active' : '' }}"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
<a href="{{ route('admin.obat') }}" class="nav-link {{ request()->routeIs('admin.obat*') ? 'active' : '' }}"><i class="fa-solid fa-pills"></i> Data Obat</a>
<a href="{{ route('admin.tindakan.index') }}"
        class="nav-link {{ request()->routeIs('admin.tindakan*') ? 'active' : '' }}"><i
            class="fa-solid fa-hand-holding-medical"></i> Manajemen Tindakan</a>
<a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="{{ route('admin.broadcast.index') }}" class="nav-link {{ request()->routeIs('admin.broadcast*') ? 'active' : '' }}"><i class="fa-solid fa-bullhorn"></i> Broadcast</a>
<a href="{{ route('admin.pembayaran') }}" class="nav-link {{ request()->routeIs('admin.pembayaran*') ? 'active' : '' }}"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
<a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="bg-primary text-white p-4 rounded-4 shadow-sm mb-4 d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.jadwal') }}" class="text-white text-decoration-none opacity-75">Jadwal</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Edit #{{ $jadwal->IdJadwal }}</li>
                    </ol>
                </nav>
                <h4 class="fw-bold m-0"><i class="fa-solid fa-calendar-day me-2"></i>Edit Detail Jadwal</h4>
            </div>
            <a href="{{ route('admin.jadwal') }}" class="btn btn-light rounded-pill px-4">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.jadwal.update', $jadwal->IdJadwal) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Read-only Information Section (Themed) --}}
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-4 border h-100">
                        <small class="text-muted d-block text-uppercase fw-bold mb-2">Informasi Dokter</small>
                        <h6 class="fw-bold mb-1">{{ $jadwal->dokter->Nama ?? '-' }}</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">{{ ucfirst($jadwal->dokter->Jabatan ?? '-') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-4 border h-100">
                        <small class="text-muted d-block text-uppercase fw-bold mb-2">Waktu Praktik</small>
                        <h6 class="fw-bold mb-1">{{ $jadwal->formatted_tanggal }}</h6>
                        <p class="mb-0 small text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $jadwal->sesi }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-4 border h-100">
                        <small class="text-muted d-block text-uppercase fw-bold mb-2">Okupansi Stok</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $jadwal->jumlah_booking_aktif }} / {{ $jadwal->Kapasitas }}</h6>
                                <small class="text-muted">Booking Terisi</small>
                            </div>
                            <div class="progress radial-progress" style="width: 40px; height: 40px;">
                                {{-- Placeholder for radial or simple progress if needed --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4 opacity-50">

            {{-- Editable Fields --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="Status" class="form-label fw-bold">Status Jadwal <span class="text-danger">*</span></label>
                    <select name="Status" id="Status" class="form-select rounded-pill px-3" required>
                        <option value="Available" {{ $jadwal->Status == 'Available' ? 'selected' : '' }}>Available (Tersedia)</option>
                        <option value="Full" {{ $jadwal->Status == 'Full' ? 'selected' : '' }}>Full (Penuh)</option>
                        <option value="Cancelled" {{ $jadwal->Status == 'Cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="Kapasitas" class="form-label fw-bold">Kapasitas Pasien <span class="text-danger">*</span></label>
                    <input type="number" name="Kapasitas" id="Kapasitas" class="form-control rounded-pill px-3" 
                           value="{{ $jadwal->Kapasitas }}" min="1" required>
                </div>
            </div>

            {{-- Booking List --}}
            @if($jadwal->bookings->count() > 0)
            <div class="card border border-light rounded-4 mb-4">
                <div class="card-header bg-transparent border-0 pt-3 px-4">
                    <h6 class="fw-bold m-0 text-dark">Pasien Terdaftar ({{ $jadwal->bookings->count() }})</h6>
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="small text-muted text-uppercase">
                                    <th>Pasien</th>
                                    <th>Status Booking</th>
                                    <th>Waktu Register</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwal->bookings as $booking)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $booking->pasien->Nama ?? '-' }}</div>
                                        <small class="text-muted">{{ $booking->IdBooking }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $bClass = 'secondary';
                                            if($booking->Status == 'PRESENT') $bClass = 'success';
                                            elseif($booking->Status == 'CANCELLED') $bClass = 'danger';
                                        @endphp
                                        <span class="badge bg-{{ $bClass }}-subtle text-{{ $bClass }} rounded-pill px-3">
                                            {{ $booking->Status }}
                                        </span>
                                    </td>
                                    <td class="small">{{ $booking->formatted_tanggal_booking }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mt-4 border-top pt-4">
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" onclick="confirmCancelJadwal()">
                    <i class="fa-solid fa-ban me-1"></i> Batalkan Seluruh Jadwal
                </button>
                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function confirmCancelJadwal() {
        Swal.fire({
            title: 'Batalkan Jadwal Ini?',
            text: "Seluruh booking aktif pada jadwal ini akan ikut dibatalkan secara otomatis! Tindakan ini tidak dapat diurungkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Batalkan Jadwal!',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('admin.jadwal.destroy', $jadwal->IdJadwal) }}';
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
