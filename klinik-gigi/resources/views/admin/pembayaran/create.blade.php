@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Proses Pembayaran')
@section('header-title', 'Billing Pasien')
@section('header-subtitle', 'Invoice untuk ' . $rekamMedis->pasien->Nama)

@section('sidebar-menu')
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('admin.booking') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="{{ route('admin.pembayaran') }}" class="nav-link active"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
<a href="{{ route('admin.laporan') }}" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('styles')
<style>
    .billing-card {
        border-radius: 15px;
        overflow: hidden;
    }
    .invoice-header {
        background: linear-gradient(135deg, #2b3a67, #4962b3);
        color: white;
        padding: 2rem;
    }
    .total-banner {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
    }
    .item-row:not(:last-child) {
        border-bottom: 1px dashed #dee2e6;
    }
</style>
@endsection

@section('content')

<div class="row g-4">
    <!-- Invoice Details -->
    <div class="col-lg-8">
        <div class="card billing-card shadow-sm border-0 bg-white">
            <div class="invoice-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1">INVOICE</h4>
                    <p class="mb-0 opacity-75">#{{ $rekamMedis->IdRekamMedis }}</p>
                </div>
                <div class="text-end">
                    <h5 class="fw-bold mb-1">Zenith Dental Clinic</h5>
                    <p class="mb-0 small opacity-75">{{ now()->isoFormat('D MMMM YYYY') }}</p>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row mb-5">
                    <div class="col-6">
                        <label class="small text-muted text-uppercase fw-bold mb-2 d-block">Ditagihkan Kepada:</label>
                        <h6 class="fw-bold mb-1">{{ $rekamMedis->pasien->Nama }}</h6>
                        <p class="text-muted small mb-0">{{ $rekamMedis->PasienID }}</p>
                        <p class="text-muted small mb-0">{{ $rekamMedis->pasien->NoTelp }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <label class="small text-muted text-uppercase fw-bold mb-2 d-block">Dokter Pemeriksa:</label>
                        <h6 class="fw-bold mb-1">{{ $rekamMedis->dokter->Nama }}</h6>
                        <small class="text-muted">{{ $rekamMedis->dokter->Jabatan }}</small>
                    </div>
                </div>

                <h6 class="fw-bold mb-3"><i class="fa-solid fa-list me-2"></i>Rincian Biaya</h6>
                
                <!-- Tindakan Table -->
                <div class="mb-4">
                    <label class="small fw-bold text-primary mb-2 d-block text-uppercase">Tindakan Medis</label>
                    @foreach($rekamMedis->tindakan as $t)
                    <div class="d-flex justify-content-between py-2 item-row">
                        <span>{{ $t->NamaTindakan }}</span>
                        <span class="fw-bold">Rp {{ number_format($t->pivot->Harga, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Obat Table -->
                <div class="mb-4">
                    <label class="small fw-bold text-info mb-2 d-block text-uppercase">Resep Obat</label>
                    @foreach($rekamMedis->obat as $o)
                    <div class="d-flex justify-content-between py-2 item-row">
                        <span>
                            {{ $o->NamaObat }}
                            <small class="text-muted d-block">{{ $o->pivot->Jumlah }}x • {{ $o->pivot->Dosis }}</small>
                        </span>
                        <span class="fw-bold">Rp {{ number_format($o->pivot->HargaSatuan * $o->pivot->Jumlah, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0">Total Tagihan</h5>
                    <h3 class="fw-bold text-primary m-0">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Action -->
    <div class="col-lg-4">
        <div class="card-custom bg-white border-0 shadow-sm sticky-top" style="top: 20px;">
            <h5 class="fw-bold mb-4">Proses Bayar</h5>
            
            <form action="{{ route('admin.pembayaran.store') }}" method="POST">
                @csrf
                <input type="hidden" name="IdRekamMedis" value="{{ $rekamMedis->IdRekamMedis }}">
                <input type="hidden" name="PasienID" value="{{ $rekamMedis->PasienID }}">
                <input type="hidden" name="TotalBayar" value="{{ $grandTotal }}">

                <div class="mb-4">
                    <label class="form-label small fw-bold">Metode Pembayaran</label>
                    <div class="d-flex flex-column gap-2">
                        <label class="border rounded p-3 d-flex align-items-center cursor-pointer">
                            <input type="radio" name="Metode" value="Tunai" class="me-3" checked>
                            <i class="fa-solid fa-money-bill-wave me-2 text-success"></i> Tunai (Cash)
                        </label>
                        <label class="border rounded p-3 d-flex align-items-center cursor-pointer">
                            <input type="radio" name="Metode" value="Transfer" class="me-3">
                            <i class="fa-solid fa-credit-card me-2 text-primary"></i> Transfer Bank / QRIS
                        </label>
                        <label class="border rounded p-3 d-flex align-items-center cursor-pointer">
                            <input type="radio" name="Metode" value="Asuransi" class="me-3">
                            <i class="fa-solid fa-shield-halved me-2 text-warning"></i> Klaim Asuransi
                        </label>
                    </div>
                </div>

                <div class="mb-4 p-3 total-banner text-center">
                    <p class="text-muted small mb-1">Anda akan menerima pembayaran sebesar:</p>
                    <h4 class="fw-bold text-success mb-0">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h4>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                    <i class="fa-solid fa-check-double me-2"></i> KONFIRMASI PEMBAYARAN
                </button>
            </form>
            
            <a href="{{ route('admin.pembayaran') }}" class="btn btn-link w-100 mt-3 text-muted text-decoration-none small">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

@endsection
