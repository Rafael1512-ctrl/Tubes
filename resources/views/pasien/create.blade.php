@extends('layouts.app')

@section('title', 'Tambah Pasien')

@section('content')
<div class="container-fluid">
    <h2>Tambah Pasien Baru</h2>
    
    <form method="POST" action="{{ route('pasien.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="Nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="Nama" name="Nama" required>
                </div>
                
                <div class="mb-3">
                    <label for="TanggalLahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="TanggalLahir" name="TanggalLahir" required>
                </div>
                
                <div class="mb-3">
                    <label for="JenisKelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-control" id="JenisKelamin" name="JenisKelamin" required>
                        <option value="">Pilih</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="Alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="Alamat" name="Alamat" rows="3" required></textarea>
                </div>
                
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
        <a href="{{ route('pasien.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection