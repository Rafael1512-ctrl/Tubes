@extends('layouts.app')

@section('title', 'Tambah Obat')

@section('content')
<div class="container">
    <div class="mb-4">
        <h2 class="text-secondary fw-bold">Tambah Obat Baru</h2>
        <p class="text-muted">Masukkan informasi obat dengan exp date</p>
    </div>

    <div class="card-custom">
        <div class="card-header-custom">Form Obat</div>
        <div class="p-4">
            <form action="{{ route('obat.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Jenis Obat <span class="text-danger">*</span></label>
                        <select name="IdJenisObat" class="form-select @error('IdJenisObat') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Obat --</option>
                            @foreach($jenisObats as $jenis)
                                <option value="{{ $jenis->JenisObatID }}" {{ old('IdJenisObat') == $jenis->JenisObatID ? 'selected' : '' }}>
                                    {{ $jenis->NamaJenis }}
                                </option>
                            @endforeach
                        </select>
                        @error('IdJenisObat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Obat <span class="text-danger">*</span></label>
                        <input type="text" name="NamaObat" class="form-control @error('NamaObat') is-invalid @enderror" 
                               value="{{ old('NamaObat') }}" placeholder="Contoh: Paracetamol 500mg" required>
                        @error('NamaObat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Satuan</label>
                        <input type="text" name="Satuan" class="form-control @error('Satuan') is-invalid @enderror" 
                               value="{{ old('Satuan') }}" placeholder="Contoh: Tablet, Strip, Box">
                        @error('Satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="Harga" class="form-control @error('Harga') is-invalid @enderror" 
                               value="{{ old('Harga') }}" min="0" step="0.01" placeholder="10000" required>
                        @error('Harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="Stok" class="form-control @error('Stok') is-invalid @enderror" 
                               value="{{ old('Stok') }}" min="0" placeholder="100" required>
                        @error('Stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Exp <span class="text-danger">*</span></label>
                        <input type="date" name="exp_date" class="form-control @error('exp_date') is-invalid @enderror" 
                               value="{{ old('exp_date') }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                               required>
                        <small class="text-muted">Minimal besok</small>
                        @error('exp_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Obat
                    </button>
                    <a href="{{ route('obat.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
