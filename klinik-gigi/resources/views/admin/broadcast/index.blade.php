@extends('layouts.dashboard')

@section('title', 'Manajemen Broadcast - Zenith Dental')
@section('header-title', 'Manajemen Broadcast')
@section('header-subtitle', 'Kirim pengumuman ke seluruh pasien atau dokter')

@section('sidebar-menu')
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('admin.booking') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.pasien') }}" class="nav-link"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
<a href="{{ route('admin.obat') }}" class="nav-link"><i class="fa-solid fa-pills"></i> Data Obat</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="{{ route('admin.broadcast.index') }}" class="nav-link active"><i class="fa-solid fa-bullhorn"></i> Broadcast</a>
<a href="{{ route('admin.pembayaran') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
<a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12 text-end">
        <a href="{{ route('admin.broadcast.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fa-solid fa-paper-plane me-2"></i> Buat Broadcast Baru
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Tanggal</th>
                    <th>Judul</th>
                    <th>Target</th>
                    <th>Pengirim</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($broadcasts as $bc)
                <tr>
                    <td class="ps-4">
                        <small class="text-muted d-block">{{ $bc->created_at->format('d M Y') }}</small>
                        <small class="text-muted">{{ $bc->created_at->format('H:i') }}</small>
                    </td>
                    <td>
                        <h6 class="fw-bold mb-0 text-dark">{{ $bc->Title }}</h6>
                        <small class="text-muted d-block">{{ Str::limit($bc->Message, 50) }}</small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $bc->TargetRole == 'all' ? 'info' : ($bc->TargetRole == 'pasien' ? 'primary' : 'warning') }} bg-opacity-10 text-{{ $bc->TargetRole == 'all' ? 'info' : ($bc->TargetRole == 'pasien' ? 'primary' : 'warning') }} rounded-pill px-2">
                            {{ ucfirst($bc->TargetRole) }}
                        </span>
                    </td>
                    <td>{{ $bc->author->name ?? '-' }}</td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-5 text-center text-muted">
                        <i class="fa-solid fa-bullhorn fa-3x mb-3 opacity-25"></i>
                        <p>Belum ada riwayat broadcast</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
