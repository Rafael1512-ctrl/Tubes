@extends('layouts.app')

@section('title', 'Data Dokter')

@section('content')
<div class="container-fluid">
    <h2>Data Dokter</h2>
    
    <a href="{{ route('pegawai.create') }}" class="btn btn-primary mb-3">Tambah Dokter</a>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Tanggal Masuk</th>
                <th>No. Telp</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawai as $p)
            <tr>
                <td>{{ $p->PegawaiID }}</td>
                <td>{{ $p->Nama }}</td>
                <td>{{ $p->Jabatan }}</td>
                <td>{{ date('d/m/Y', strtotime($p->TanggalMasuk)) }}</td>
                <td>{{ $p->NoTelp }}</td>
                <td>{{ $p->email }}</td>
                <td>
                    <a href="{{ route('pegawai.show', $p->PegawaiID) }}" class="btn btn-sm btn-info">Detail</a>
                    <a href="{{ route('pegawai.edit', $p->PegawaiID) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('pegawai.destroy', $p->PegawaiID) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dokter ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $pegawai->links() }}
</div>
@endsectionI