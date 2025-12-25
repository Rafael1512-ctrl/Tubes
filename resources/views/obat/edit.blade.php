@extends('layouts.app')

@section('title', 'Edit Obat')

@section('content')
<div class="container">
    <div class="mb-4">
        <h2 class="text-secondary fw-bold">Edit Obat</h2>
        <p class="text-muted">Update informasi obat: {{ $obat->NamaObat }}</p>
    </div>

    <div class="card-custom">
        <div class="card-header-custom">Form Edit Obat</div>
        <div class="p-4">
            <form action="{{ route('obat.update', $obat->IdObat) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Jenis Obat <span class="text-danger">*</span></label>
                        <select name="IdJenisObat" class="form-select @error('IdJenisObat') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Obat --</option>
                            @foreach($jenisObats as $jenis)
                                <option value="{{ $jenis->JenisObatID }}" 
                                    {{ old('IdJenisObat', $obat->IdJenisObat) == $jenis->JenisObatID ? 'selected' : '' }}>
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
                               value="{{ old('NamaObat', $obat->NamaObat) }}" required>
                        @error('NamaObat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Satuan</label>
                        <input type="text" name="Satuan" class="form-control @error('Satuan') is-invalid @enderror" 
                               value="{{ old('Satuan', $obat->Satuan) }}">
                        @error('Satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="Harga" class="form-control @error('Harga') is-invalid @enderror" 
                               value="{{ old('Harga', $obat->Harga) }}" min="0" step="0.01" required>
                        @error('Harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="Stok" class="form-control @error('Stok') is-invalid @enderror" 
                               value="{{ old('Stok', $obat->Stok) }}" min="0" required>
                        @error('Stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Exp <span class="text-danger">*</span></label>
                        <input type="date" name="exp_date" class="form-control @error('exp_date') is-invalid @enderror" 
                               value="{{ old('exp_date', $obat->exp_date ? $obat->exp_date->format('Y-m-d') : '') }}" 
                               required>
                        @error('exp_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($obat->isExpired())
                            <div class="alert alert-danger mt-2 mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>Obat ini sudah EXPIRED!
                            </div>
                        @elseif($obat->isExpiringSoon(30))
                            <div class="alert alert-warning mt-2 mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>Obat ini akan expired dalam 30 hari!
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Obat
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
