@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Manajemen Tindakan')
@section('header-title', 'Manajemen Tindakan')
@section('header-subtitle', 'Kelola daftar layanan dan tindakan medis klinik')

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i
            class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.jadwal') }}"
        class="nav-link {{ request()->routeIs(['admin.jadwal*', 'admin.booking*']) ? 'active' : '' }}"><i
            class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
    <a href="{{ route('admin.pasien') }}" class="nav-link {{ request()->routeIs('admin.pasien*') ? 'active' : '' }}"><i
            class="fa-solid fa-hospital-user"></i> Data Pasien</a>
    <a href="{{ route('admin.obat') }}" class="nav-link {{ request()->routeIs('admin.obat*') ? 'active' : '' }}"><i
            class="fa-solid fa-pills"></i> Data Obat</a>
    <a href="{{ route('admin.tindakan.index') }}"
        class="nav-link {{ request()->routeIs('admin.tindakan*') ? 'active' : '' }}"><i
            class="fa-solid fa-hand-holding-medical"></i> Manajemen Tindakan</a>
    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i
            class="fa-solid fa-users"></i> Manajemen User</a>
    <a href="{{ route('admin.broadcast.index') }}"
        class="nav-link {{ request()->routeIs('admin.broadcast*') ? 'active' : '' }}"><i class="fa-solid fa-bullhorn"></i>
        Broadcast</a>
    <a href="{{ route('admin.pembayaran') }}"
        class="nav-link {{ request()->routeIs('admin.pembayaran*') ? 'active' : '' }}"><i
            class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
    <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}"><i
            class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check me-3 fa-lg"></i>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-custom mb-4 shadow-sm border-0 rounded-4">
        <form action="{{ route('admin.tindakan.index') }}" method="GET" class="row g-3 p-2">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3"><i
                            class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 rounded-end-pill px-3"
                        placeholder="Cari nama tindakan..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select rounded-pill px-3 shadow-none border-1">
                    <option value="">Semua Dokter</option>
                    <option value="dokter_gigi" {{ request('role') == 'dokter_gigi' ? 'selected' : '' }}>Dokter Gigi</option>
                    <option value="dokter_spesialis" {{ request('role') == 'dokter_spesialis' ? 'selected' : '' }}>Dokter
                        Spesialis</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select rounded-pill px-3 shadow-none border-1">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary rounded-pill w-100 shadow-sm"><i
                        class="fa-solid fa-filter me-1"></i></button>
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('admin.tindakan.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm w-100">
                    <i class="fa-solid fa-plus me-1"></i> Tambah
                </a>
            </div>
        </form>
    </div>

    <div class="card-custom shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0">
                <thead class="bg-light border-bottom">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Tindakan</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tindakans as $t)
                        <tr>
                            <td class="ps-4"><code>{{ $t->IdTindakan }}</code></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $t->NamaTindakan }}</div>
                                @if($t->Durasi)
                                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $t->Durasi }}</small>
                                @endif
                            </td>
                            <td><span
                                    class="badge bg-primary-subtle text-primary px-3 rounded-pill">{{ $t->Kategori ?? '-' }}</span>
                            </td>
                            <td class="fw-bold text-success">Rp {{ number_format($t->Harga, 0, ',', '.') }}</td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.tindakan.edit', $t->IdTindakan) }}"
                                        class="btn btn-sm btn-outline-primary rounded-circle action-btn" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle action-btn"
                                        title="Hapus"
                                        onclick="confirmDelete('{{ route('admin.tindakan.destroy', $t->IdTindakan) }}', '{{ $t->NamaTindakan }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="fa-solid fa-folder-open fa-3x opacity-25"></i></div>
                                Data tindakan tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tindakans->hasPages())
            <div class="p-4 border-top bg-light">
                {{ $tindakans->onEachSide(1)->links() }}
            </div>
        @endif
    </div>

    <style>
        .action-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }
    </style>

    @push('scripts')
        <script>
            function confirmDelete(url, name) {
                Swal.fire({
                    title: 'Hapus Tindakan?',
                    html: `Apakah Anda yakin ingin menghapus <span class="text-danger fw-bold">${name}</span>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-4 shadow-lg border-0',
                        confirmButton: 'rounded-pill px-4',
                        cancelButton: 'rounded-pill px-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;
                        form.innerHTML = `
                            @csrf
                            @method('DELETE')
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        </script>
    @endpush
@endsection