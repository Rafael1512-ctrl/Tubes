@extends('layouts.app')

@section('title', 'Manajemen Obat')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-secondary fw-bold">Manajemen Obat</h2>
            <p class="text-muted">Kelola obat dengan tracking exp date</p>
        </div>
        <a href="{{ route('obat.create') }}" class="btn btn-primary rounded-pill"><i class="fas fa-plus me-2"></i>Tambah Obat</a>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $filter == 'all' ? 'active' : '' }}" href="{{ route('obat.index', ['filter' => 'all']) }}">
                <i class="fas fa-list me-2"></i>Semua
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $filter == 'valid' ? 'active' : '' }}" href="{{ route('obat.index', ['filter' => 'valid']) }}">
                <i class="fas fa-check-circle me-2"></i>Valid
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $filter == 'expiring' ? 'active' : '' }}" href="{{ route('obat.index', ['filter' => 'expiring']) }}">
                <i class="fas fa-exclamation-triangle me-2"></i>Expiring Soon (30 hari)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $filter == 'expired' ? 'active' : '' }}" href="{{ route('obat.index', ['filter' => 'expired']) }}">
                <i class="fas fa-times-circle me-2"></i>Expired
            </a>
        </li>
    </ul>

    <div class="card-custom">
        <div class="card-header-custom">Daftar Obat</div>
        <div class="p-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID Obat</th>
                        <th>Nama Obat</th>
                        <th>Jenis</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Exp Date</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obats as $obat)
                        <tr>
                            <td><code>{{ $obat->IdObat }}</code></td>
                            <td>{{ $obat->NamaObat }}</td>
                            <td>{{ $obat->jenisObat->NamaJenis ?? '-' }}</td>
                            <td>{{ $obat->Satuan }}</td>
                            <td>Rp {{ number_format($obat->Harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $obat->Stok < 10 ? 'bg-warning' : 'bg-success' }}">
                                    {{ $obat->Stok }}
                                </span>
                            </td>
                            <td>
                                @if($obat->exp_date)
                                    {{ \Carbon\Carbon::parse($obat->exp_date)->format('d M Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $status = $obat->getExpiryStatus();
                                @endphp
                                @if($status == 'expired')
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Expired</span>
                                @elseif($status == 'expiring-soon')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>Expiring Soon</span>
                                @elseif($status == 'valid')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Valid</span>
                                @else
                                    <span class="badge bg-secondary">No Expiry</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('obat.edit', $obat->IdObat) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('obat.destroy', $obat->IdObat) }}" method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus obat ini?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Tidak ada obat ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $obats->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
