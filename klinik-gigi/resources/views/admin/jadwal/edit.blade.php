@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Edit Jadwal Dokter')
@section('header-title','Edit Jadwal Dokter')
@section('header-subtitle','Ubah status dan kapasitas jadwal')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link active"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.jadwal.update', $jadwal->IdJadwal) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Read-only Information --}}
            <div class="alert alert-light border">
                <h6 class="fw-bold mb-3">Informasi Jadwal</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>ID Jadwal:</strong> {{ $jadwal->IdJadwal }}</p>
                        <p class="mb-2"><strong>Dokter:</strong> {{ $jadwal->dokter->Nama ?? '-' }}</p>
                        <p class="mb-2"><strong>Jabatan:</strong> {{ ucfirst($jadwal->dokter->Jabatan ?? '-') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Tanggal:</strong> {{ $jadwal->formatted_tanggal }}</p>
                        <p class="mb-2"><strong>Jam:</strong> {{ $jadwal->formatted_jam }}</p>
                        <p class="mb-2"><strong>Sesi:</strong> {{ $jadwal->sesi }}</p>
                    </div>
                </div>
            </div>

            {{-- Editable Fields --}}
            <div class="mb-3">
                <label for="Status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                <select name="Status" id="Status" class="form-select" required>
                    <option value="Available" {{ $jadwal->Status == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Full" {{ $jadwal->Status == 'Full' ? 'selected' : '' }}>Full</option>
                    <option value="Cancelled" {{ $jadwal->Status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="Kapasitas" class="form-label fw-bold">Kapasitas</label>
                <input type="number" name="Kapasitas" id="Kapasitas" class="form-control" 
                       value="{{ $jadwal->Kapasitas }}" min="1">
                <small class="text-muted">
                    Saat ini: {{ $jadwal->jumlah_booking_aktif }} booking aktif, 
                    sisa {{ $jadwal->sisa_kapasitas }} slot
                </small>
            </div>

            {{-- Booking List --}}
            @if($jadwal->bookings->count() > 0)
            <div class="mb-3">
                <label class="form-label fw-bold">Daftar Booking ({{ $jadwal->bookings->count() }})</label>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>ID Booking</th>
                                <th>Pasien</th>
                                <th>Tanggal Booking</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwal->bookings as $booking)
                            <tr>
                                <td>{{ $booking->IdBooking }}</td>
                                <td>{{ $booking->pasien->Nama ?? '-' }}</td>
                                <td>{{ $booking->formatted_tanggal_booking }}</td>
                                <td>
                                    <span class="badge bg-{{ $booking->Status == 'CANCELLED' ? 'danger' : 'success' }}">
                                        {{ $booking->Status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Update Jadwal
                </button>
                <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <form action="{{ route('admin.jadwal.destroy', $jadwal->IdJadwal) }}" method="POST" 
                      onsubmit="return confirm('Yakin ingin membatalkan jadwal ini?')" class="ms-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-ban"></i> Batalkan Jadwal
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>

@endsection
