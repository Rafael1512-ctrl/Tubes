@extends('layouts.app')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="container-fluid">
    <h2>Detail Rekam Medis</h2>
    
    <div class="card mb-3">
        <div class="card-header">Informasi Umum</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Rekam Medis:</strong> {{ $rekamMedis->IdRekamMedis }}</p>
                    <p><strong>Tanggal:</strong> {{ date('d/m/Y', strtotime($rekamMedis->Tanggal)) }}</p>
                    <p><strong>Pasien:</strong> {{ $rekamMedis->pasien->Nama }}</p>
                    <p><strong>Dokter:</strong> {{ $rekamMedis->dokter->Nama }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Anamnesa:</strong> {{ $rekamMedis->Anamnesa }}</p>
                    <p><strong>Diagnosa:</strong> {{ $rekamMedis->Diagnosa }}</p>
                    <p><strong>Catatan:</strong> {{ $rekamMedis->Catatan }}</p>
                    <p><strong>Resep Dokter:</strong> {{ $rekamMedis->ResepDokter }}</p>
                </div>
            </div>
        </div>
    </div>
    
    @if($rekamMedis->obat->count() > 0)
    <div class="card mb-3">
        <div class="card-header">Resep Obat</div>
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Dosis</th>
                        <th>Frekuensi</th>
                        <th>Lama (hari)</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekamMedis->obat as $obat)
                    <tr>
                        <td>{{ $obat->NamaObat }}</td>
                        <td>{{ $obat->pivot->Dosis }}</td>
                        <td>{{ $obat->pivot->Frekuensi }}</td>
                        <td>{{ $obat->pivot->LamaHari }}</td>
                        <td>{{ $obat->pivot->Jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    
    @if($rekamMedis->tindakan->count() > 0)
    <div class="card mb-3">
        <div class="card-header">Tindakan</div>
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Nama Tindakan</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekamMedis->tindakan as $tindakan)
                    <tr>
                        <td>{{ $tindakan->NamaTindakan }}</td>
                        <td>{{ $tindakan->pivot->Jumlah }}</td>
                        <td>Rp {{ number_format($tindakan->pivot->Harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    
    <a href="{{ route('rekam-medis.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection