@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Manajemen Pembayaran')
@section('header-title', 'Billing & Pembayaran')
@section('header-subtitle', 'Proses invoice untuk pasien yang telah selesai diperiksa')

@section('sidebar-menu')
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('admin.booking') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.pasien') }}" class="nav-link"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
<a href="{{ route('admin.obat') }}" class="nav-link"><i class="fa-solid fa-pills"></i> Data Obat</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="{{ route('admin.pembayaran') }}" class="nav-link active"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
<a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success border-0 shadow-sm mb-4">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
</div>
@endif

<!-- Nav Pills for Tabs -->
@php
    $activeTab = request('tab', (request('month') || request('year')) ? 'history' : 'unpaid');
@endphp

<ul class="nav nav-pills mb-4" id="pembayaranTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab == 'unpaid' ? 'active' : '' }} rounded-pill px-4" id="unpaid-tab" data-bs-toggle="pill" data-bs-target="#unpaid" type="button" role="tab">
            <i class="fa-solid fa-hourglass-half me-2"></i>Menunggu Pembayaran
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab == 'history' ? 'active' : '' }} rounded-pill px-4 ms-2" id="history-tab" data-bs-toggle="pill" data-bs-target="#history" type="button" role="tab">
            <i class="fa-solid fa-clock-rotate-left me-2"></i>Riwayat Transaksi
        </button>
    </li>
</ul>

<div class="tab-content" id="pembayaranTabContent">
    <!-- Tab 1: Unpaid -->
    <div class="tab-pane fade {{ $activeTab == 'unpaid' ? 'show active' : '' }}" id="unpaid" role="tabpanel">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-file-invoice me-2 text-primary"></i>Invoice Pending</h5>
                <span class="badge bg-primary">{{ $unpaidRekamMedis->count() }} Data</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID RM</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Tanggal Periksa</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unpaidRekamMedis as $rm)
                        <tr>
                            <td><code>{{ $rm->IdRekamMedis }}</code></td>
                            <td>
                                <div class="fw-bold">{{ $rm->pasien->Nama ?? '-' }}</div>
                                <small class="text-muted">{{ $rm->PasienID }}</small>
                            </td>
                            <td>{{ $rm->dokter->Nama ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($rm->Tanggal)->isoFormat('D MMM YYYY') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.pembayaran.create', $rm->IdRekamMedis) }}" class="btn btn-primary btn-sm px-3 rounded-pill">
                                    Proses Bayar
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">Semua tagihan telah selesai diproses.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 2: History -->
    <div class="tab-pane fade {{ $activeTab == 'history' ? 'show active' : '' }}" id="history" role="tabpanel">
        <div class="card-custom">
            <!-- Filter Row -->
            <form action="{{ route('admin.pembayaran') }}" method="GET" class="row g-3 mb-4">
                <input type="hidden" name="tab" value="history">
                <div class="col-md-4">
                    <select name="month" class="form-select rounded-pill">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="year" class="form-control rounded-pill" value="{{ $year }}" placeholder="Tahun">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-dark rounded-pill w-100"><i class="fa-solid fa-filter me-2"></i>Filter Data</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Nota</th>
                            <th>Pasien</th>
                            <th>Metode</th>
                            <th>Waktu Bayar</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paidHistory as $pay)
                        <tr>
                            <td><code>{{ $pay->IdPembayaran }}</code></td>
                            <td>{{ $pay->pasien->Nama ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark font-monospace">{{ $pay->Metode }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($pay->TanggalPembayaran)->isoFormat('D MMM YYYY, HH:mm') }}</td>
                            <td class="fw-bold text-success">Rp {{ number_format($pay->TotalBayar, 0, ',', '.') }}</td>
                            <td><span class="badge bg-success-subtle text-success border-0 px-3 rounded-pill">Lunas</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada riwayat pembayaran untuk periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
