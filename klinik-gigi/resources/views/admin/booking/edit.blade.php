@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Edit Booking')
@section('header-title','Edit Booking Pasien')
@section('header-subtitle','Ubah status booking')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.booking') }}" class="nav-link active"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.booking.update', $booking->IdBooking) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Read-only Information --}}
            <div class="alert alert-light border">
                <h6 class="fw-bold mb-3">Informasi Booking</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>ID Booking:</strong> {{ $booking->IdBooking }}</p>
                        <p class="mb-2"><strong>Pasien:</strong> {{ $booking->pasien->Nama ?? '-' }}</p>
                        <p class="mb-2"><strong>No. Telp:</strong> {{ $booking->pasien->NoTelp ?? '-' }}</p>
                        <p class="mb-2"><strong>Tanggal Booking:</strong> {{ $booking->formatted_tanggal_booking }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Dokter:</strong> {{ $booking->jadwal->dokter->Nama ?? '-' }}</p>
                        <p class="mb-2"><strong>Jabatan:</strong> {{ ucfirst($booking->jadwal->dokter->Jabatan ?? '-') }}</p>
                        <p class="mb-2"><strong>Tanggal Jadwal:</strong> {{ $booking->jadwal->formatted_tanggal ?? '-' }}</p>
                        <p class="mb-2"><strong>Jam:</strong> {{ $booking->jadwal->formatted_jam ?? '-' }}</p>
                        <p class="mb-2"><strong>Sesi:</strong> 
                            <span class="badge bg-{{ $booking->jadwal->sesi == 'Pagi' ? 'warning' : 'primary' }}">
                                {{ $booking->jadwal->sesi ?? '-' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Editable Field --}}
            <div class="mb-3">
                <label for="Status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                <select name="Status" id="Status" class="form-select" required>
                    <option value="PRESENT" {{ $booking->Status == 'PRESENT' ? 'selected' : '' }}>Present</option>
                    <option value="CANCELLED" {{ $booking->Status == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Update Booking
                </button>
                <a href="{{ route('admin.booking') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                @if($booking->Status != 'CANCELLED')
                <form action="{{ route('admin.booking.destroy', $booking->IdBooking) }}" method="POST" 
                      onsubmit="return confirm('Yakin ingin membatalkan booking ini?')" class="ms-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-ban"></i> Batalkan Booking
                    </button>
                </form>
                @endif
            </div>
        </form>
    </div>
</div>

@endsection
