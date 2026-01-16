@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Manajemen Data Obat')
@section('header-title', 'Inventory Obat')
@section('header-subtitle', 'Kelola stok dan harga obat klinik')

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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-custom mb-4">
        <form action="{{ route('admin.obat') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control rounded-pill px-3"
                    placeholder="Cari nama atau kode obat..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select rounded-pill px-3">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisObats as $j)
                        <option value="{{ $j->JenisObatID }}" {{ request('jenis') == $j->JenisObatID ? 'selected' : '' }}>
                            {{ $j->NamaJenis }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary rounded-pill w-100"><i class="fa-solid fa-search me-1"></i>
                    Filter</button>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('admin.obat.create') }}" class="btn btn-success rounded-pill px-4"><i
                        class="fa-solid fa-plus me-1"></i> Tambah Obat</a>
            </div>
        </form>
    </div>

    <div class="card-custom">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Obat</th>
                        <th>Jenis</th>
                        <th>Satuan</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obats as $obat)
                        <tr>
                            <td><code>{{ $obat->IdObat }}</code></td>
                            <td>
                                <div class="fw-bold">{{ $obat->NamaObat }}</div>
                            </td>
                            <td><span class="badge bg-info-subtle text-info">{{ $obat->jenisObat->NamaJenis ?? '-' }}</span>
                            </td>
                            <td>{{ $obat->Satuan }}</td>
                            <td class="text-muted small">Rp {{ number_format($obat->HargaBeli ?? 0, 0, ',', '.') }}</td>
                            <td class="fw-bold text-primary">Rp {{ number_format($obat->Harga ?? 0, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @php
                                    $stokClass = 'success';
                                    if ($obat->Stok <= 10)
                                        $stokClass = 'danger';
                                    elseif ($obat->Stok <= 30)
                                        $stokClass = 'warning';
                                @endphp
                                <span class="badge bg-{{ $stokClass }}-subtle text-{{ $stokClass }} px-3 py-2 rounded-pill">
                                    {{ $obat->Stok }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3"
                                        onclick="showAddStockModal('{{ $obat->IdObat }}', '{{ $obat->NamaObat }}', '{{ $obat->Satuan }}')">
                                        <i class="fa-solid fa-plus me-1"></i> Stok
                                    </button>
                                    <a href="{{ route('admin.obat.edit', $obat->IdObat) }}"
                                        class="btn btn-sm btn-outline-primary rounded-circle"
                                        style="width: 32px; height: 32px;"><i class="fa-solid fa-pen p-1"></i></a>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle"
                                        style="width: 32px; height: 32px;"
                                        onclick="confirmDelete('{{ route('admin.obat.destroy', $obat->IdObat) }}', '{{ $obat->NamaObat }}')">
                                        <i class="fa-solid fa-trash p-1"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Data obat tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $obats->links() }}
        </div>
    </div>

    <!-- Add Stock Modal -->
    <div class="modal fade" id="addStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold m-0">Tambah Stok Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addStockForm" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="text-center mb-4">
                            <div class="bg-success-subtle text-success p-3 rounded-circle d-inline-block mb-2">
                                <i class="fa-solid fa-pills fa-2x"></i>
                            </div>
                            <h6 id="obatName" class="fw-bold mb-1">Nama Obat</h6>
                            <small class="text-muted" id="obatId">Kode: -</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Tambah</label>
                            <div class="input-group">
                                <input type="number" name="tambah_stok" class="form-control rounded-start-pill px-3"
                                    placeholder="Masukkan jumlah..." required min="1">
                                <span class="input-group-text rounded-end-pill px-3 bg-light" id="obatSatuan">Satuan</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4">Update Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showAddStockModal(id, name, satuan) {
                const modal = new bootstrap.Modal(document.getElementById('addStockModal'));
                document.getElementById('obatName').innerText = name;
                document.getElementById('obatId').innerText = 'Kode: ' + id;
                document.getElementById('obatSatuan').innerText = satuan;
                document.getElementById('addStockForm').action = `{{ url('admin/obat') }}/${id}/add-stock`;
                modal.show();
            }

            function confirmDelete(url, name) {
                Swal.fire({
                    title: 'Hapus Obat?',
                    html: `Apakah Anda yakin ingin menghapus <span class="text-primary fw-bold">${name}</span>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
                    icon: 'warning',
                    iconColor: '#f87171',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="fa-solid fa-trash-can me-2"></i>Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-5 shadow-lg border-0 p-4',
                        confirmButton: 'rounded-pill px-4 py-2 fw-bold shadow-sm',
                        cancelButton: 'rounded-pill px-4 py-2 fw-bold'
                    },
                    backdrop: `rgba(15, 23, 42, 0.4)`
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;
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
@endsection