@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Edit Tindakan')
@section('header-title', 'Edit Tindakan')
@section('header-subtitle', 'Perbarui detail layanan medis')

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
    <a href="{{ route('admin.tindakan.index') }}" class="nav-link active"><i class="fa-solid fa-hand-holding-medical"></i>
        Manajemen Tindakan</a>
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-custom shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning-subtle text-warning p-3 rounded-circle me-3">
                            <i class="fa-solid fa-pen-to-square fa-xl"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold m-0">Perbarui Detail Tindakan</h5>
                            <small class="text-muted">ID Tindakan: <code>{{ $tindakan->IdTindakan }}</code></small>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.tindakan.update', $tindakan->IdTindakan) }}" method="POST" class="p-4 pt-0">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold opacity-75">ID Tindakan (Tidak dapat diubah)</label>
                            <input type="text" class="form-control rounded-pill px-3 bg-light"
                                value="{{ $tindakan->IdTindakan }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <datalist id="kategoris">
                                @foreach($kategoris as $k)
                                    <option value="{{ $k }}">
                                @endforeach
                            </datalist>
                            <input type="text" name="Kategori" list="kategoris"
                                class="form-control rounded-pill px-3 @error('Kategori') is-invalid @enderror"
                                value="{{ old('Kategori', $tindakan->Kategori) }}"
                                placeholder="Pilih atau ketik kategori baru...">
                            @error('Kategori') <div class="invalid-feedback ms-3">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Tindakan</label>
                        <input type="text" name="NamaTindakan"
                            class="form-control rounded-pill px-3 @error('NamaTindakan') is-invalid @enderror"
                            value="{{ old('NamaTindakan', $tindakan->NamaTindakan) }}" required
                            placeholder="Contoh: Scaling Gigi">
                        @error('NamaTindakan') <div class="invalid-feedback ms-3">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Harga Layanan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light rounded-start-pill border-end-0 ps-3">Rp</span>
                                <input type="number" name="Harga"
                                    class="form-control border-start-0 @error('Harga') is-invalid @enderror"
                                    value="{{ old('Harga', $tindakan->Harga) }}" required min="0">
                                <span class="input-group-text bg-light rounded-end-pill border-start-0 pe-3">.00</span>
                            </div>
                            @error('Harga') <div class="text-danger small mt-1 ms-3">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Estimasi Durasi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light rounded-start-pill border-end-0 ps-3"><i
                                        class="fa-regular fa-clock text-muted"></i></span>
                                <input type="time" name="Durasi"
                                    class="form-control border-start-0 rounded-end-pill px-3 @error('Durasi') is-invalid @enderror"
                                    value="{{ old('Durasi', $tindakan->Durasi) }}">
                            </div>
                            @error('Durasi') <div class="invalid-feedback ms-3">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <a href="{{ route('admin.tindakan.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                        <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 shadow-sm fw-bold">
                            <i class="fa-solid fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection