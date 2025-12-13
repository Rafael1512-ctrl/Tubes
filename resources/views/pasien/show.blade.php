@extends('layouts.app')

@section('title', 'Detail Pasien')

@section('content')
<div class="container-fluid">
    <h2>Detail Pasien</h2>
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Pasien:</strong> {{ $pasien->PasienID }}</p>
                    <p><strong>Nama:</strong> {{ $pasien->Nama }}</p>
                    <p><strong>Tanggal Lahir:</strong> {{ date('d/m/Y', strtotime($pasien->TanggalLahir)) }}</p>
                    <p><strong>Jenis Kelamin:</strong> {{ $pasien->JenisKelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Alamat:</strong> {{ $pasien->Alamat }}</p>
                    <p><strong>No. Telepon:</strong> {{ $pasien->NoTelp }}</p>
                    <p><strong>Email:</strong> {{ $pasien->email }}</p>
                    <p><strong>Tanggal Daftar:</strong> {{ $pasien->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <a href="{{ route('pasien.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection