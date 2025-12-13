@extends('layouts.app')

@section('title', 'Rekam Medis')

@section('content')
<div class="container-fluid">
    <h2>Rekam Medis</h2>
    
    <a href="{{ route('rekam-medis.create') }}" class="btn btn-primary mb-3">Tambah Rekam Medis</a>
    
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
                <th>Dokter</th>
                <th>Diagnosa</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekamMedis as $rm)
            <tr>
                <td>{{ $rm->IdRekamMedis }}</td>
                <td>{{ date('d/m/Y', strtotime($rm->Tanggal)) }}</td>
                <td>{{ $rm->pasien->Nama }}</td>
                <td>{{ $rm->dokter->Nama }}</td>
                <td>{{ Str::limit($rm->Diagnosa, 50) }}</td>
                <td>
                    <a href="{{ route('rekam-medis.show', $rm->IdRekamMedis) }}" class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $rekamMedis->links() }}
</div>
@endsection