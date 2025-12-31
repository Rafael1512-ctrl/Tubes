@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Data Pasien')
@section('header-title','Data Pasien')
@section('header-subtitle','Daftar seluruh pasien klinik')

@section('sidebar-menu')
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('admin.booking') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.pasien') }}" class="nav-link active"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
<a href="{{ route('admin.obat') }}" class="nav-link"><i class="fa-solid fa-pills"></i> Data Obat</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
<a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('content')

<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0"><i class="fa-solid fa-users-medical me-2 text-primary"></i>Database Pasien</h5>
        <form action="{{ route('admin.pasien') }}" method="GET" class="d-flex gap-2">
            <div class="input-group">
                <input type="text" name="search" class="form-control rounded-start-pill px-3" placeholder="Nama atau ID Pasien..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary rounded-end-pill px-4"><i class="fa-solid fa-search"></i></button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID Pasien</th>
                    <th>Nama Lengkap</th>
                    <th>Detail Personel</th>
                    <th>Kontak</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasiens as $pasien)
                <tr>
                    <td><code>{{ $pasien->PasienID }}</code></td>
                    <td>
                        <div class="fw-bold text-dark">{{ $pasien->Nama }}</div>
                        <small class="text-muted">Umur: {{ $pasien->TanggalLahir ? \Carbon\Carbon::parse($pasien->TanggalLahir)->age . ' Tahun' : '-' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border-0">{{ $pasien->JenisKelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        <div class="small text-muted text-truncate mt-1" style="max-width: 150px;" title="{{ $pasien->Alamat }}">{{ $pasien->Alamat }}</div>
                    </td>
                    <td>
                        <div class="small fw-semibold"><i class="fa-solid fa-phone small me-1"></i>{{ $pasien->NoTelp }}</div>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.pasien.history', $pasien->PasienID) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat Medis
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-user-slash fa-3x opacity-25 mb-3 d-block"></i>
                        Tidak ada data pasien ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $pasiens->links() }}
    </div>
</div>

@endsection
