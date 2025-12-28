@extends('layouts.dashboard')

@section('theme','dokter')
@section('title','Data Pasien')
@section('header-title','Data Pasien')
@section('header-subtitle','Daftar seluruh pasien klinik')

@section('sidebar-menu')
<a href="/dokter/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('dokter.jadwal') }}" class="nav-link"><i class="fa-solid fa-calendar-week"></i> Jadwal Saya</a>
<a href="{{ route('dokter.pasien') }}" class="nav-link active"><i class="fa-solid fa-user-injured"></i> Data Pasien</a>
<a href="{{ route('dokter.riwayat') }}" class="nav-link"><i class="fa-solid fa-history"></i> Riwayat Praktek</a>
@endsection

@section('content')

<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0">Daftar Pasien</h5>
        <form action="{{ route('dokter.pasien') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau ID..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID Pasien</th>
                    <th>Nama Pasien</th>
                    <th>Jenis Kelamin</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasiens as $pasien)
                <tr>
                    <td><code>{{ $pasien->PasienID }}</code></td>
                    <td>
                        <div class="fw-bold">{{ $pasien->Nama }}</div>
                        <small class="text-muted">{{ $pasien->TanggalLahir ? \Carbon\Carbon::parse($pasien->TanggalLahir)->age . ' Tahun' : '-' }}</small>
                    </td>
                    <td>{{ $pasien->JenisKelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $pasien->NoTelp }}</td>
                    <td><div class="text-truncate" style="max-width: 200px;">{{ $pasien->Alamat }}</div></td>
                    <td class="text-center">
                        <a href="{{ route('dokter.pasien.history', $pasien->PasienID) }}" class="btn btn-info btn-sm text-white">
                            <i class="fa-solid fa-history me-1"></i> Rekam Medis
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Data pasien tidak ditemukan</td>
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
