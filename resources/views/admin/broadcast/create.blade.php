@extends('layouts.dashboard')

@section('title', 'Buat Broadcast - Zenith Dental')
@section('header-title', 'Buat Broadcast')

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
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <form action="{{ route('admin.broadcast.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Judul Pengumuman</label>
                    <input type="text" name="Title" class="form-control rounded-3" placeholder="Contoh: Libur Hari Raya" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Target Penerima</label>
                    <select name="TargetRole" class="form-select rounded-3" required>
                        <option value="all">Semua User</option>
                        <option value="pasien" selected>Hanya Pasien</option>
                        <option value="dokter">Hanya Dokter</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Isi Pesan</label>
                    <textarea name="Message" class="form-control rounded-3" rows="5" placeholder="Tulis pesan lengkap di sini..." required></textarea>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.broadcast.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-paper-plane me-2"></i> Kirim Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
