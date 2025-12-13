@extends('layouts.app')

@section('title', 'Tambah Dokter')

@section('content')
<div class="container-fluid">
    <h2>Tambah Dokter Baru</h2>
    
    <form method="POST" action="{{ route('pegawai.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="Nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="Nama" name="Nama" required>
                </div>
                
                <div class="mb-3">
                    <label for="Jabatan" class="form-label">Jabatan</label>
                    <select class="form-control" id="Jabatan" name="Jabatan" required>
                        <option value="">Pilih</option>
                        <option value="Dokter Gigi">Dokter Gigi</option>
                        <option value="Dokter Gigi Spesialis">Dokter Gigi Spesialis</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="TanggalMasuk" class="form-label">Tanggal Masuk</label>
                    <input type="date" class="form-control" id="TanggalMasuk" name="TanggalMasuk" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="NoTelp" class="form-label">No. Telepon</label>
                    <input type="text" class="form-control" id="NoTelp" name="NoTelp" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection