@extends('layouts.dashboard')

@section('theme','dokter')
@section('title','Input Rekam Medis')
@section('header-title','Input Rekam Medis')
@section('header-subtitle','Isi data pemeriksaan pasien dengan lengkap')

@section('sidebar-menu')
<a href="/dokter/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('dokter.jadwal') }}" class="nav-link"><i class="fa-solid fa-calendar-week"></i> Jadwal Saya</a>
<a href="{{ route('dokter.pasien') }}" class="nav-link"><i class="fa-solid fa-user-injured"></i> Data Pasien</a>
<a href="{{ route('dokter.riwayat') }}" class="nav-link"><i class="fa-solid fa-history"></i> Riwayat Praktek</a>
@endsection

@section('styles')
<style>
    .section-title {
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6c757d;
        margin-bottom: 1rem;
        letter-spacing: 0.5px;
    }
    
    .price-tag {
        font-family: 'Courier New', monospace;
        font-weight: bold;
    }
</style>
@endsection

@section('content')

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Gagal!</strong> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form action="{{ route('dokter.rekam-medis.store') }}" method="POST" id="rmForm">
    @csrf
    <input type="hidden" name="IdBooking" value="{{ $booking->IdBooking }}">

    <div class="row g-4">
        <!-- Kolom Kiri: Info Pasien & Diagnosa -->
        <div class="col-lg-4">
            
            <!-- Info Pasien -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $booking->pasien->Nama }}</h5>
                            <small class="text-muted">{{ $booking->pasien->PasienID }}</small>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">Tanggal Lahir / Usia</small>
                        <span class="fw-medium">
                            {{ $booking->pasien->TanggalLahir ? \Carbon\Carbon::parse($booking->pasien->TanggalLahir)->format('d M Y') : '-' }} 
                            ({{ $booking->pasien->TanggalLahir ? \Carbon\Carbon::parse($booking->pasien->TanggalLahir)->age . ' Tahun' : '-' }})
                        </span>
                    </div>
                    <div class="mb-2">
                         <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">Alamat</small>
                         <span class="fw-medium">{{ $booking->pasien->Alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Diagnosa -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="section-title"><i class="fa-solid fa-stethoscope me-2"></i> Anamnesa & Diagnosa</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Diagnosa Utama <span class="text-danger">*</span></label>
                        <textarea name="Diagnosa" class="form-control" rows="4" placeholder="Tuliskan diagnosa medis..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Catatan Tambahan</label>
                        <textarea name="Catatan" class="form-control" rows="3" placeholder="Keluhan pasien, riwayat alergi, dll..."></textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Tindakan & Obat -->
        <div class="col-lg-8">
            
            <!-- Tindakan -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="section-title mb-0"><i class="fa-solid fa-user-doctor me-2"></i> Tindakan Medis</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addTindakan()">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Tindakan
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless" id="tindakanTable">
                        <thead class="text-muted small border-bottom">
                            <tr>
                                <th style="width: 60%">Jenis Tindakan</th>
                                <th style="width: 30%">Estimasi Biaya</th>
                                <th style="width: 10%"></th>
                            </tr>
                        </thead>
                        <tbody id="tindakanContainer">
                            {{-- Rows will be added here via JS --}}
                        </tbody>
                    </table>
                    <div id="emptyTindakan" class="text-center py-3 text-muted border rounded border-dashed bg-light">
                        <small>Belum ada tindakan yang dipilih</small>
                    </div>
                </div>
            </div>

            <!-- Resep Obat -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="section-title mb-0"><i class="fa-solid fa-capsules me-2"></i> Resep Obat</h6>
                         <button type="button" class="btn btn-sm btn-outline-success" onclick="addObat()">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Obat
                        </button>
                    </div>
                </div>
                <div class="card-body">
                     <table class="table table-borderless" id="obatTable">
                        <thead class="text-muted small border-bottom">
                            <tr>
                                <th style="width: 35%">Nama Obat</th>
                                <th style="width: 15%">Qty</th>
                                <th style="width: 20%">Dosis</th>
                                <th style="width: 25%">Cara Pakai</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody id="obatContainer">
                             {{-- Rows will be added here via JS --}}
                        </tbody>
                    </table>
                    <div id="emptyObat" class="text-center py-3 text-muted border rounded border-dashed bg-light">
                        <small>Belum ada obat yang diresepkan</small>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="d-flex justify-content-end mt-4 gap-2">
                <a href="/dokter/dashboard" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold">
                    <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Rekam Medis
                </button>
            </div>

        </div>
    </div>
</form>

{{-- Hidden Templates for JS --}}
<template id="tindakanRowTemplate">
    <tr>
        <td>
            <select name="tindakan[]" class="form-select action-select" onchange="updatePrice(this)" required>
                <option value="">-- Pilih Tindakan --</option>
                @foreach($tindakans as $kategori => $items)
                    <optgroup label="{{ $kategori ?: 'Umum' }}">
                        @foreach($items as $t)
                            <option value="{{ $t->IdTindakan }}" data-price="{{ $t->Harga }}">{{ $t->NamaTindakan }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" class="form-control-plaintext price-display" value="Rp 0" readonly>
        </td>
        <td class="text-end">
            <button type="button" class="btn btn-sm btn-icon btn-danger-soft text-danger" onclick="removeRow(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<template id="obatRowTemplate">
    <tr>
        <td>
            <select name="obat[INDEX][id]" class="form-select" onchange="checkStock(this)" required>
                <option value="">-- Pilih Obat --</option>
                @foreach($obats as $o)
                    <option value="{{ $o->IdObat }}" data-stok="{{ $o->Stok }}">{{ $o->NamaObat }} (Stok: {{ $o->Stok }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="obat[INDEX][qty]" class="form-control" placeholder="1" min="1" value="1" required>
        </td>
         <td>
            <input type="text" name="obat[INDEX][dosis]" class="form-control" placeholder="3x1" required>
        </td>
         <td>
            <input type="text" name="obat[INDEX][frekuensi]" class="form-control" placeholder="Sesudah makan">
        </td>
        <td class="text-end">
            <button type="button" class="btn btn-sm btn-icon btn-danger-soft text-danger" onclick="removeRow(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    let obatIndex = 0;

    function addTindakan() {
        document.getElementById('emptyTindakan').style.display = 'none';
        const template = document.getElementById('tindakanRowTemplate');
        const clone = template.content.cloneNode(true);
        document.getElementById('tindakanContainer').appendChild(clone);
    }

    function addObat() {
        document.getElementById('emptyObat').style.display = 'none';
        const template = document.getElementById('obatRowTemplate');
        let html = template.innerHTML.replace(/INDEX/g, obatIndex++);
        
        const container = document.getElementById('obatContainer');
        const row = document.createElement('tr');
        row.innerHTML = html;
        container.appendChild(row);
    }

    function removeRow(btn) {
        const row = btn.closest('tr');
        const tbody = row.parentElement;
        row.remove();
        
        if(tbody.id === 'tindakanContainer' && tbody.children.length === 0) {
            document.getElementById('emptyTindakan').style.display = 'block';
        }
        if(tbody.id === 'obatContainer' && tbody.children.length === 0) {
            document.getElementById('emptyObat').style.display = 'block';
        }
    }

    function updatePrice(select) {
        const option = select.options[select.selectedIndex];
        const price = option.getAttribute('data-price');
        const row = select.closest('tr');
        const priceInput = row.querySelector('.price-display');
        
        if (price) {
            priceInput.value = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
        } else {
            priceInput.value = 'Rp 0';
        }
    }
</script>

@endsection
