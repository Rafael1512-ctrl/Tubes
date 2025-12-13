@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container-fluid">
    <h2>Data Pembayaran</h2>
    
    <a href="{{ route('pembayaran.create') }}" class="btn btn-primary mb-3">Tambah Pembayaran</a>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pasien</th>
                <th>Rekam Medis</th>
                <th>Metode</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayaran as $p)
            <tr>
                <td>{{ $p->IdPembayaran }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($p->TanggalPembayaran)) }}</td>
                <td>{{ $p->pasien->Nama }}</td>
                <td>{{ $p->IdRekamMedis }}</td>
                <td>{{ $p->Metode }}</td>
                <td>Rp {{ number_format($p->TotalBayar, 0, ',', '.') }}</td>
                <td>
                    @if($p->Status == 'PAID')
                        <span class="badge bg-success">Lunas</span>
                    @else
                        <span class="badge bg-warning">{{ $p->Status }}</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('pembayaran.show', $p->IdPembayaran) }}" class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $pembayaran->links() }}
</div>
@endsection